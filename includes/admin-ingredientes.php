<?php
/**
 * Admin Ingredientes: Página de administração para o dicionário de ingredientes mestre e conversões
 */

defined('ABSPATH') || exit;

// Registrar Menu no Admin
function sts_receitas_add_ingredients_menu() {
    add_menu_page(
        'Ingredientes',
        'Ingredientes',
        'manage_options',
        'sts-ingredientes',
        'sts_receitas_render_ingredients_admin',
        'dashicons-food',
        26
    );
}
add_action('admin_menu', 'sts_receitas_add_ingredients_menu');

// Renderizar a tela administrativa
function sts_receitas_render_ingredients_admin() {
    global $wpdb;
    
    $table_ing = $wpdb->prefix . 'receita_ingredientes_mestre';
    $table_conv = $wpdb->prefix . 'receita_ingredientes_conversoes';
    
    $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // Lista de unidades de medidas domésticas para conversão
    $convertible_units = array(
        'xicara_cha'  => 'Xícara de Chá',
        'colher_sopa' => 'Colher de Sopa',
        'colher_cha'  => 'Colher de Chá',
        'colher_cafe' => 'Colher de Café',
        'unidade'     => 'Unidade',
        'fatia'       => 'Fatia',
        'dente'       => 'Dente',
        'ramo'        => 'Ramo'
    );
    
    // Processamento do Upload e Importação do CSV com Regra de Não Duplicidade (Upsert)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['importar_csv']) && isset($_POST['sts_csv_nonce'])) {
        if (!wp_verify_nonce($_POST['sts_csv_nonce'], 'sts_importar_csv')) {
            wp_die('Ação não autorizada.');
        }
        
        if (isset($_FILES['ingredientes_csv']) && $_FILES['ingredientes_csv']['error'] === UPLOAD_ERR_OK) {
            $file_path = $_FILES['ingredientes_csv']['tmp_name'];
            
            if (($handle = fopen($file_path, "r")) !== FALSE) {
                // Ler e ignorar a linha de cabeçalho
                fgetcsv($handle, 1000, ",");
                
                $imported_count = 0;
                $updated_count = 0;
                
                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (empty($row[0])) continue;
                    
                    $name          = sanitize_text_field($row[0]);
                    $slug          = !empty($row[1]) ? sanitize_title($row[1]) : sanitize_title($name);
                    $food_category = isset($row[2]) ? sanitize_text_field($row[2]) : '';
                    $kcal          = isset($row[3]) ? floatval($row[3]) : 0.0;
                    $carbs         = isset($row[4]) ? floatval($row[4]) : 0.0;
                    $protein       = isset($row[5]) ? floatval($row[5]) : 0.0;
                    $fat           = isset($row[6]) ? floatval($row[6]) : 0.0;
                    $fiber         = isset($row[7]) ? floatval($row[7]) : 0.0;
                    $sodium        = isset($row[8]) ? floatval($row[8]) : 0.0;
                    $is_allergen   = (isset($row[9]) && intval($row[9]) === 1) ? 1 : 0;
                    $allergen_type = isset($row[10]) ? sanitize_text_field($row[10]) : '';
                    
                    $data = array(
                        'name'               => $name,
                        'slug'               => $slug,
                        'food_category'      => $food_category,
                        'density_g_per_ml'   => 1.0000,
                        'kcal_per_100g'      => $kcal,
                        'protein_g_per_100g' => $protein,
                        'carbs_g_per_100g'   => $carbs,
                        'fat_g_per_100g'     => $fat,
                        'fiber_g_per_100g'   => $fiber,
                        'sodium_mg_per_100g' => $sodium,
                        'is_allergen'        => $is_allergen,
                        'allergen_type'      => $allergen_type
                    );
                    
                    // Verificação de ingrediente existente por SLUG para evitar duplicados (Upsert)
                    $existing_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_ing WHERE slug = %s", $slug));
                    
                    if ($existing_id) {
                        $wpdb->update($table_ing, $data, array('id' => $existing_id));
                        $ing_id = $existing_id;
                        $updated_count++;
                    } else {
                        $wpdb->insert($table_ing, $data);
                        $ing_id = $wpdb->insert_id;
                        $imported_count++;
                    }
                    
                    if ($ing_id > 0) {
                        // Limpar antigas conversões
                        $wpdb->delete($table_conv, array('ingredient_id' => $ing_id));
                        
                        // Inserir conversões se especificadas no CSV
                        if (isset($row[11]) && floatval($row[11]) > 0) {
                            $wpdb->insert($table_conv, array('ingredient_id' => $ing_id, 'unit' => 'xicara_cha', 'grams_equivalent' => floatval($row[11])));
                        }
                        if (isset($row[12]) && floatval($row[12]) > 0) {
                            $wpdb->insert($table_conv, array('ingredient_id' => $ing_id, 'unit' => 'colher_sopa', 'grams_equivalent' => floatval($row[12])));
                        }
                        if (isset($row[13]) && floatval($row[13]) > 0) {
                            $wpdb->insert($table_conv, array('ingredient_id' => $ing_id, 'unit' => 'colher_cha', 'grams_equivalent' => floatval($row[13])));
                        }
                    }
                }
                fclose($handle);
                wp_redirect(admin_url("admin.php?page=sts-ingredientes&message=imported&imported={$imported_count}&updated={$updated_count}"));
                exit;
            }
        }
    }

    // Processamento de Ações de POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sts_ingrediente_nonce'])) {
        if (!wp_verify_nonce($_POST['sts_ingrediente_nonce'], 'sts_salvar_ingrediente')) {
            wp_die('Ação não autorizada.');
        }
        
        if (isset($_POST['salvar_ingrediente'])) {
            $name          = sanitize_text_field($_POST['name']);
            $slug          = !empty($_POST['slug']) ? sanitize_title($_POST['slug']) : sanitize_title($name);
            $food_category = sanitize_text_field($_POST['food_category']);
            $density       = floatval($_POST['density_g_per_ml']) ?: 1.0;
            $kcal          = floatval($_POST['kcal_per_100g']);
            $protein       = floatval($_POST['protein_g_per_100g']);
            $carbs         = floatval($_POST['carbs_g_per_100g']);
            $fat           = floatval($_POST['fat_g_per_100g']);
            $fiber         = floatval($_POST['fiber_g_per_100g']);
            $sodium        = floatval($_POST['sodium_mg_per_100g']);
            $is_allergen   = isset($_POST['is_allergen']) ? 1 : 0;
            $allergen_type = sanitize_text_field($_POST['allergen_type']);
            
            $data = array(
                'name'               => $name,
                'slug'               => $slug,
                'food_category'      => $food_category,
                'density_g_per_ml'   => $density,
                'kcal_per_100g'      => $kcal,
                'protein_g_per_100g' => $protein,
                'carbs_g_per_100g'   => $carbs,
                'fat_g_per_100g'     => $fat,
                'fiber_g_per_100g'   => $fiber,
                'sodium_mg_per_100g' => $sodium,
                'is_allergen'        => $is_allergen,
                'allergen_type'      => $allergen_type
            );
            
            if ($id > 0) {
                // Editar Ingrediente
                $wpdb->update($table_ing, $data, array('id' => $id));
                $ing_id = $id;
            } else {
                // Adicionar Ingrediente
                $wpdb->insert($table_ing, $data);
                $ing_id = $wpdb->insert_id;
            }
            
            // Salvar Equivalências de Unidades (Conversões)
            if ($ing_id > 0) {
                // Limpar conversões antigas primeiro
                $wpdb->delete($table_conv, array('ingredient_id' => $ing_id));
                
                if (isset($_POST['conversoes']) && is_array($_POST['conversoes'])) {
                    foreach ($_POST['conversoes'] as $unit => $grams) {
                        $grams_val = floatval($grams);
                        if ($grams_val > 0 && array_key_exists($unit, $convertible_units)) {
                            $wpdb->insert($table_conv, array(
                                'ingredient_id'    => $ing_id,
                                'unit'             => $unit,
                                'grams_equivalent' => $grams_val
                            ));
                        }
                    }
                }
            }
            
            wp_redirect(admin_url('admin.php?page=sts-ingredientes&message=saved'));
            exit;
        }
    }
    
    // Processamento de Ações via GET (Deletar)
    if ($action === 'delete' && $id > 0) {
        check_admin_referer('delete_ingrediente_' . $id);
        
        // Deleta o ingrediente e suas relações
        $wpdb->delete($table_ing, array('id' => $id));
        // Conversões são limpas via ON DELETE CASCADE (se suportado pelo InnoDB) ou manualmente
        $wpdb->delete($table_conv, array('ingredient_id' => $id));
        
        wp_redirect(admin_url('admin.php?page=sts-ingredientes&message=deleted'));
        exit;
    }
    
    // Renderização do HTML de acordo com a Ação
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Ingredientes Mestre</h1>
        
        <?php if (isset($_GET['message']) && $_GET['message'] === 'saved') : ?>
            <div class="notice notice-success is-dismissible"><p>Ingrediente salvo com sucesso!</p></div>
        <?php endif; ?>
        <?php if (isset($_GET['message']) && $_GET['message'] === 'deleted') : ?>
            <div class="notice notice-success is-dismissible"><p>Ingrediente excluído com sucesso!</p></div>
        <?php endif; ?>
        <?php if (isset($_GET['message']) && $_GET['message'] === 'imported') : 
            $imp = intval($_GET['imported'] ?? 0);
            $upd = intval($_GET['updated'] ?? 0);
        ?>
            <div class="notice notice-success is-dismissible">
                <p>Importação de CSV concluída! <strong><?php echo $imp; ?></strong> ingredientes novos cadastrados e <strong><?php echo $upd; ?></strong> ingredientes existentes atualizados (sem duplicações).</p>
            </div>
        <?php endif; ?>
        
        <?php if ($action === 'add' || $action === 'edit') : 
            $item = null;
            $convs = array();
            if ($id > 0) {
                $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_ing WHERE id = %d", $id));
                $raw_convs = $wpdb->get_results($wpdb->prepare("SELECT unit, grams_equivalent FROM $table_conv WHERE ingredient_id = %d", $id));
                foreach ($raw_convs as $c) {
                    $convs[$c->unit] = $c->grams_equivalent;
                }
            }
            ?>
            <a href="?page=sts-ingredientes" class="page-title-action">Voltar para Listagem</a>
            <hr class="wp-header-end">
            
            <div class="card" style="max-width: 800px; margin-top: 20px; padding: 20px;">
                <h2><?php echo $id > 0 ? 'Editar Ingrediente' : 'Adicionar Novo Ingrediente'; ?></h2>
                <form method="post" action="">
                    <?php wp_nonce_field('sts_salvar_ingrediente', 'sts_ingrediente_nonce'); ?>
                    
                    <table class="form-table" role="presentation">
                        <tbody>
                            <tr>
                                <th scope="row"><label for="name">Nome do Ingrediente</label></th>
                                <td>
                                    <input name="name" type="text" id="name" value="<?php echo $item ? esc_attr($item->name) : ''; ?>" class="regular-text" required>
                                    <p class="description">Ex: Farinha de Trigo, Manteiga Sem Sal, Leite Integral.</p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="slug">Slug (URL)</label></th>
                                <td>
                                    <input name="slug" type="text" id="slug" value="<?php echo $item ? esc_attr($item->slug) : ''; ?>" class="regular-text" placeholder="Gerado automaticamente se vazio">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="food_category">Categoria Alimentar</label></th>
                                <td>
                                    <input name="food_category" type="text" id="food_category" value="<?php echo $item ? esc_attr($item->food_category) : ''; ?>" class="regular-text" placeholder="Ex: Laticínio, Carne, Açúcar, Tempero">
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="density_g_per_ml">Densidade (g/ml)</label></th>
                                <td>
                                    <input name="density_g_per_ml" type="number" step="0.0001" min="0" id="density_g_per_ml" value="<?php echo $item ? esc_attr($item->density_g_per_ml) : '1.0000'; ?>" class="small-text">
                                    <p class="description">Necessário para conversões automáticas peso <-> volume (ex: Óleo tem densidade ~0.92, Água é 1.0).</p>
                                </td>
                            </tr>
                            
                            <!-- Nutrição -->
                            <tr>
                                <th scope="row"><strong>Nutrição por 100g</strong></th>
                                <td>
                                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; max-width: 500px;">
                                        <p style="margin:0;"><label>Kcal:</label><br><input name="kcal_per_100g" type="number" step="0.01" value="<?php echo $item ? esc_attr($item->kcal_per_100g) : '0.00'; ?>" style="width:90%"></p>
                                        <p style="margin:0;"><label>Carboidratos (g):</label><br><input name="carbs_g_per_100g" type="number" step="0.01" value="<?php echo $item ? esc_attr($item->carbs_g_per_100g) : '0.00'; ?>" style="width:90%"></p>
                                        <p style="margin:0;"><label>Proteínas (g):</label><br><input name="protein_g_per_100g" type="number" step="0.01" value="<?php echo $item ? esc_attr($item->protein_g_per_100g) : '0.00'; ?>" style="width:90%"></p>
                                        <p style="margin:0;"><label>Gorduras (g):</label><br><input name="fat_g_per_100g" type="number" step="0.01" value="<?php echo $item ? esc_attr($item->fat_g_per_100g) : '0.00'; ?>" style="width:90%"></p>
                                        <p style="margin:0;"><label>Fibras (g):</label><br><input name="fiber_g_per_100g" type="number" step="0.01" value="<?php echo $item ? esc_attr($item->fiber_g_per_100g) : '0.00'; ?>" style="width:90%"></p>
                                        <p style="margin:0;"><label>Sódio (mg):</label><br><input name="sodium_mg_per_100g" type="number" step="0.01" value="<?php echo $item ? esc_attr($item->sodium_mg_per_100g) : '0.00'; ?>" style="width:90%"></p>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Alérgenos -->
                            <tr>
                                <th scope="row">Informações de Alérgenos</th>
                                <td>
                                    <label><input name="is_allergen" type="checkbox" id="is_allergen" value="1" <?php checked($item && $item->is_allergen, 1); ?>> Este ingrediente é um alérgeno comum</label>
                                    <br><br>
                                    <label for="allergen_type">Tipo de Alérgeno:</label><br>
                                    <input name="allergen_type" type="text" id="allergen_type" value="<?php echo $item ? esc_attr($item->allergen_type) : ''; ?>" class="regular-text" placeholder="Ex: Glúten, Lactose, Amendoim">
                                </td>
                            </tr>
                            
                            <!-- Conversões de Medidas Caseiras -->
                            <tr>
                                <th scope="row"><strong>Equivalência de Medidas Caseiras (Conversão)</strong></th>
                                <td>
                                    <p class="description">Defina quantos gramas equivale uma porção doméstica deste ingrediente específico.</p>
                                    <br>
                                    <div style="max-width: 500px;">
                                        <?php foreach ($convertible_units as $unit_key => $unit_name) : ?>
                                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 8px; border-bottom: 1px solid #f0f0f0; padding-bottom: 5px;">
                                                <span>1 <?php echo esc_html($unit_name); ?> é igual a:</span>
                                                <span>
                                                    <input name="conversoes[<?php echo esc_attr($unit_key); ?>]" type="number" step="0.01" value="<?php echo isset($convs[$unit_key]) ? esc_attr($convs[$unit_key]) : ''; ?>" style="width: 80px; text-align:right;"> gramas
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <p class="submit">
                        <input type="submit" name="salvar_ingrediente" id="submit" class="button button-primary" value="Salvar Ingrediente">
                    </p>
                </form>
            </div>
            
        <?php else : 
            // LISTAGEM DOS INGREDIENTES
            $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
            $paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
            $per_page = 20;
            $offset = ($paged - 1) * $per_page;
            
            $where = 'WHERE 1=1';
            $params = array();
            if (!empty($search)) {
                $where .= ' AND name LIKE %s';
                $params[] = '%' . $wpdb->esc_like($search) . '%';
            }
            
            $query_count = "SELECT COUNT(*) FROM $table_ing $where";
            $query_items = "SELECT * FROM $table_ing $where ORDER BY name ASC LIMIT %d OFFSET %d";
            
            if (!empty($params)) {
                $total_items = $wpdb->get_var($wpdb->prepare($query_count, $params));
                $items = $wpdb->get_results($wpdb->prepare($query_items, array_merge($params, array($per_page, $offset))));
            } else {
                $total_items = $wpdb->get_var($query_count);
                $items = $wpdb->get_results($wpdb->prepare($query_items, array($per_page, $offset)));
            }
            
            $total_pages = ceil($total_items / $per_page);
            ?>
            
            <a href="?page=sts-ingredientes&action=add" class="page-title-action">Adicionar Novo</a>
            <hr class="wp-header-end">
            
            <form method="get" action="" style="margin: 15px 0; display: flex; gap: 10px;">
                <input type="hidden" name="page" value="sts-ingredientes">
                <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Buscar ingredientes...">
                <input type="submit" class="button" value="Buscar">
            </form>
            
            <table class="wp-list-table widefat fixed striped table-view-list">
                <thead>
                    <tr>
                        <th scope="col" class="manage-column column-name">Nome</th>
                        <th scope="col" class="manage-column column-category">Categoria</th>
                        <th scope="col" class="manage-column column-nutrition">Calorias (100g)</th>
                        <th scope="col" class="manage-column column-allergen">Alérgeno</th>
                        <th scope="col" class="manage-column column-conversions">Medidas Configuradas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)) : ?>
                        <tr>
                            <td colspan="5">Nenhum ingrediente cadastrado.</td>
                        </tr>
                    <?php else : 
                        foreach ($items as $item) : 
                            // Contar conversões
                            $num_convs = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table_conv WHERE ingredient_id = %d", $item->id));
                            ?>
                            <tr>
                                <td>
                                    <strong><a href="?page=sts-ingredientes&action=edit&id=<?php echo $item->id; ?>"><?php echo esc_html($item->name); ?></a></strong>
                                    <div class="row-actions">
                                        <span class="edit"><a href="?page=sts-ingredientes&action=edit&id=<?php echo $item->id; ?>">Editar</a> | </span>
                                        <span class="trash"><a href="<?php echo wp_nonce_url('?page=sts-ingredientes&action=delete&id=' . $item->id, 'delete_ingrediente_' . $item->id); ?>" class="submitdelete" onclick="return confirm('Deseja realmente excluir este ingrediente?');">Excluir</a></span>
                                    </div>
                                </td>
                                <td><?php echo esc_html($item->food_category ?: '—'); ?></td>
                                <td><?php echo esc_html($item->kcal_per_100g); ?> kcal</td>
                                <td>
                                    <?php 
                                    if ($item->is_allergen) {
                                        echo '<span class="dashicons dashicons-warning" style="color:#d63638;" title="' . esc_attr($item->allergen_type) . '"></span> ' . esc_html($item->allergen_type ?: 'Alérgeno');
                                    } else {
                                        echo 'Não';
                                    }
                                    ?>
                                </td>
                                <td><?php echo $num_convs > 0 ? $num_convs . ' medidas' : '<span style="color:#ec5b13;">Falta configurar equivalência</span>'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <?php if ($total_pages > 1) : ?>
                <div class="tablenav bottom">
                    <div class="tablenav-pages">
                        <span class="displaying-num"><?php echo $total_items; ?> itens</span>
                        <span class="pagination-links">
                            <?php for ($i = 1; $i <= $total_pages; $i++) : 
                                $class = ($i === $paged) ? 'current-page' : '';
                                ?>
                                <a class="page-numbers <?php echo $class; ?>" href="?page=sts-ingredientes&paged=<?php echo $i; ?>&s=<?php echo esc_attr($search); ?>"><?php echo $i; ?></a>
                            <?php endfor; ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 📥 FORMULÁRIO DE IMPORTAÇÃO DE CSV -->
            <div class="card" style="margin-top: 30px; padding: 20px; max-width: 600px; border: 1px solid #ccd0d4; border-radius: 8px; background: #fff;">
                <h2>Importar Ingredientes via CSV</h2>
                <p class="description">Suba um arquivo CSV para cadastrar vários ingredientes de uma vez. A regra de <strong>Upsert</strong> está ativa: se o ingrediente (slug) já existir, suas calorias e medidas serão atualizadas em vez de duplicadas.</p>
                <br>
                <form method="post" action="" enctype="multipart/form-data">
                    <?php wp_nonce_field('sts_importar_csv', 'sts_csv_nonce'); ?>
                    <input type="file" name="ingredientes_csv" accept=".csv" required style="margin-bottom: 15px; display: block;">
                    <input type="submit" name="importar_csv" class="button button-secondary" value="Iniciar Importação CSV">
                </form>
            </div>
            
        <?php endif; ?>
    </div>
    <?php
}
