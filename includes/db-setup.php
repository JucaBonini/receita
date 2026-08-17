<?php
/**
 * DB Setup: Criação das tabelas customizadas MySQL para ingredientes e conversões
 */

defined('ABSPATH') || exit;

function sts_setup_receitas_custom_tables() {
    global $wpdb;

    // Versão atual do banco de dados do tema
    $db_version = '1.0.0';
    $installed_ver = get_option('sts_receitas_db_version');

    // Só roda a criação se a versão atual instalada for diferente da versão do tema
    if ($installed_ver !== $db_version) {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $collate = $wpdb->get_charset_collate();

        // 1. Tabela de Ingredientes Mestre
        $table_ingredientes = $wpdb->prefix . 'receita_ingredientes_mestre';
        $sql_ingredientes = "CREATE TABLE $table_ingredientes (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            slug varchar(160) NOT NULL,
            name varchar(160) NOT NULL,
            food_category varchar(80) DEFAULT NULL,
            density_g_per_ml decimal(8,4) NOT NULL DEFAULT '1.0000',
            kcal_per_100g decimal(8,2) NOT NULL DEFAULT '0.00',
            protein_g_per_100g decimal(8,2) NOT NULL DEFAULT '0.00',
            carbs_g_per_100g decimal(8,2) NOT NULL DEFAULT '0.00',
            fat_g_per_100g decimal(8,2) NOT NULL DEFAULT '0.00',
            fiber_g_per_100g decimal(8,2) NOT NULL DEFAULT '0.00',
            sodium_mg_per_100g decimal(8,2) NOT NULL DEFAULT '0.00',
            is_allergen tinyint(1) NOT NULL DEFAULT '0',
            allergen_type varchar(60) DEFAULT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY slug (slug),
            KEY name (name)
        ) $collate;";

        // 2. Tabela de Conversões de Unidades por Ingrediente
        $table_conversoes = $wpdb->prefix . 'receita_ingredientes_conversoes';
        $sql_conversoes = "CREATE TABLE $table_conversoes (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            ingredient_id bigint(20) NOT NULL,
            unit varchar(30) NOT NULL,
            grams_equivalent decimal(10,4) NOT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY uq_ing_unit (ingredient_id,unit)
        ) $collate;";

        // 3. Tabela de Relacionamento Ingredientes <-> Receitas
        $table_rel = $wpdb->prefix . 'receita_ingredientes_rel';
        $sql_rel = "CREATE TABLE $table_rel (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            recipe_id bigint(20) NOT NULL,
            ingredient_id bigint(20) NOT NULL,
            group_name varchar(80) NOT NULL DEFAULT 'Ingredientes',
            amount decimal(10,3) DEFAULT NULL,
            unit varchar(30) NOT NULL,
            display_text varchar(160) DEFAULT NULL,
            sort_order int(11) NOT NULL DEFAULT '0',
            PRIMARY KEY  (id),
            KEY recipe_id (recipe_id),
            KEY ingredient_id (ingredient_id)
        ) $collate;";

        // Executar dbDelta para criar/atualizar as tabelas de forma segura
        dbDelta($sql_ingredientes);
        dbDelta($sql_conversoes);
        dbDelta($sql_rel);

        // Atualizar opção no banco de dados para evitar re-execução
        update_option('sts_receitas_db_version', $db_version);
    }
}
add_action('after_setup_theme', 'sts_setup_receitas_custom_tables');
