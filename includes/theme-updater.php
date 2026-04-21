<?php
/**
 * STS Theme Updater - GitHub Integration
 * Gerencia atualizações automáticas do tema diretamente via GitHub.
 */

class STS_Theme_Updater {
    private $theme_slug;
    private $current_version;
    private $repo;

    public function __construct($repo) {
        $this->theme_slug = get_template();
        $theme = wp_get_theme($this->theme_slug);
        $this->current_version = $theme->get('Version');
        $this->repo = $repo;

        add_filter('site_transient_update_themes', [$this, 'check_update']);
    }

    public function check_update($transient) {
        if (empty($transient->checked)) return $transient;

        $remote_version = $this->get_remote_version();
        
        if ($remote_version && version_compare($this->current_version, $remote_version, '<')) {
            $res = [
                'theme'       => $this->theme_slug,
                'new_version' => $remote_version,
                'url'         => "https://github.com/{$this->repo}",
                'package'     => "https://github.com/{$this->repo}/archive/refs/heads/main.zip",
            ];
            $transient->response[$this->theme_slug] = $res;
        }

        return $transient;
    }

    private function get_remote_version() {
        $url = "https://raw.githubusercontent.com/{$this->repo}/main/style.css";
        $response = wp_remote_get($url);
        
        if (is_wp_error($response)) return false;

        $content = wp_remote_retrieve_body($response);
        preg_match('/Version:\s*(.*)/', $content, $matches);
        
        return isset($matches[1]) ? trim($matches[1]) : false;
    }
}

// Inicializa o Updater para o Tema
new STS_Theme_Updater('JucaBonini/receita');
