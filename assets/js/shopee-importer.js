jQuery(document).ready(function($) {
    // Inicialização do Importador Shopee
    var $urlInput = $('input[name="sts_product_url"]');
    if ($urlInput.length) {
        // Inserir botão e área de status no DOM
        $urlInput.after('<button type="button" id="sts_fetch_shopee_btn" class="button button-secondary" style="margin-top: 10px; display: inline-flex; align-items: center; gap: 5px;"><span class="dashicons dashicons-search" style="margin-top:2px; font-size:16px; width:16px; height:16px;"></span> Buscar Dados da Shopee</button><div id="sts_shopee_status" style="margin-top: 10px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;"></div>');

        $('#sts_fetch_shopee_btn').on('click', function(e) {
            e.preventDefault();
            var url = $urlInput.val();
            if (!url) {
                alert('Por favor, insira uma URL de produto da Shopee no campo acima.');
                return;
            }

            var $status = $('#sts_shopee_status');
            var $btn = $(this);
            var post_id = $('#post_ID').val();

            // Bloquear botão e iniciar carregamento
            $btn.prop('disabled', true);
            $status.css('color', '#ec5b13').text('⏳ Conectando à API Shopee...');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'sts_fetch_shopee_product',
                    product_url: url,
                    post_id: post_id,
                    nonce: sts_shopee_importer.nonce
                },
                success: function(response) {
                    $btn.prop('disabled', false);
                    if (response.success) {
                        $status.css('color', 'green').text('✅ Importado com sucesso!');
                        
                        // Atualizar Título se houver
                        if (response.data.title) {
                            $('#title').val(response.data.title);
                            // Suporte ao editor moderno (Gutenberg)
                            if ($('.editor-post-title__input').length) {
                                $('.editor-post-title__input').val(response.data.title).trigger('change');
                            }
                        }
                        
                        // Atualizar Preço
                        if (response.data.price) {
                            $('input[name="sts_product_price"]').val(response.data.price);
                        }
                        
                        // Atualizar Link (caso seja retornado o link encurtado oficial)
                        if (response.data.affiliate_link) {
                            $urlInput.val(response.data.affiliate_link);
                        }
                        
                        // Forçar atualização do visual da imagem de capa (Featured Image)
                        if (response.data.image_id && response.data.image_url) {
                            $status.html('✅ Importado com sucesso!<br><span style="font-size:10px; color:#888; text-transform:none;">(A imagem do produto foi salva como capa. Atualize/Salve a página para ver o visual atualizado no WordPress)</span>');
                        }
                    } else {
                        $status.css('color', 'red').text('❌ Erro: ' + response.data);
                        alert('Erro ao buscar dados: ' + response.data);
                    }
                },
                error: function() {
                    $btn.prop('disabled', false);
                    $status.css('color', 'red').text('❌ Erro de conexão.');
                    alert('Erro de conexão ao servidor WordPress.');
                }
            });
        });
    }
});
