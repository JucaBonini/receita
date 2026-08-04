<?php
/**
 * theme-config.php - Configuracoes Centralizadas do Tema
 *
 * Todas as constantes e valores padrao do tema definidos aqui.
 * Edite este arquivo para atualizar configuracoes globais sem
 * precisar buscar strings espalhadas pelo codigo.
 *
 * @package Descomplicando Receitas
 * @since   2.9.0
 */

defined('ABSPATH') || exit;

// =====================================================================
// IDENTIDADE DO SITE
// =====================================================================
define('STS_CHEF_NAME',      'Mary Rodrigues');
define('STS_CHEF_JOB_TITLE', 'Chef e Especialista Culinaria');

// =====================================================================
// REDES SOCIAIS DA ORGANIZACAO (valores padrao - sobrescrevia via get_option)
// =====================================================================
if (!defined('STS_ORG_INSTAGRAM')) define('STS_ORG_INSTAGRAM', 'https://www.instagram.com/descomplicandoreceitasofic');
if (!defined('STS_ORG_FACEBOOK'))  define('STS_ORG_FACEBOOK',  'https://www.facebook.com/descomplicandoreceitasofic');
if (!defined('STS_ORG_YOUTUBE'))   define('STS_ORG_YOUTUBE',   'https://www.youtube.com/@descomplicandoreceitas');
if (!defined('STS_ORG_TIKTOK'))    define('STS_ORG_TIKTOK',    'https://www.tiktok.com/@desc_receitas_ofic');
if (!defined('STS_ORG_PINTEREST')) define('STS_ORG_PINTEREST', 'https://www.pinterest.com/descomplicandoreceitas');

// =====================================================================
// TRACKING E ANALYTICS
// =====================================================================
if (!defined('STS_TIKTOK_PIXEL_ID')) define('STS_TIKTOK_PIXEL_ID', 'D7O1A03C77U9UIR3GP2G');

// =====================================================================
// RECEITAS - VALORES PADRAO (FALLBACKS)
// =====================================================================
define('STS_RECIPE_DEFAULT_PREP_TIME',   20);
define('STS_RECIPE_DEFAULT_COOK_TIME',   30);
define('STS_RECIPE_DEFAULT_CUISINE',     'Brasileira');
define('STS_RECIPE_DEFAULT_DIFFICULTY',  'Facil');
define('STS_RECIPE_DEFAULT_YIELD',       '4 porcoes');

// =====================================================================
// PERFORMANCE
// =====================================================================
define('STS_HERO_CACHE_TTL',         HOUR_IN_SECONDS);
define('STS_SEMANTIC_MAP_TTL',       DAY_IN_SECONDS);
define('STS_HERO_MAX_CANDIDATES',    30);
define('STS_SEMANTIC_MAP_MAX_POSTS', 200);
define('STS_SEMANTIC_MAX_LINKS',     4);

// =====================================================================
// SEGURANCA - RATE LIMITING
// =====================================================================
define('STS_LOGIN_MAX_ATTEMPTS', 5);
define('STS_LOGIN_RATE_WINDOW',  15 * MINUTE_IN_SECONDS);
