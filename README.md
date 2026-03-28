# WordPress Theme: STS Recipe 2 (Premium 2026)

Este é um tema de receitas de alta performance, construído com **Tailwind CSS**, focado em **SEO 2026 (EEAT)** e monetização estratégica.

## 🚀 Principais Funcionalidades

- **Gerenciador de Anúncios Cirúrgico (Native Ad Inserter):** Controle total de injeção de anúncios antes/depois de parágrafos e cabeçalhos (H2/H3).
- **Dashboard do Usuário:** Sistema de favoritos dinâmico com grid paginado (6 itens por página) via LocalStorage/AJAX.
- **Sistema de Avaliação de Receitas:** Widget de estrelas otimizado para Schema.org (JSON-LD) e Google Discover.
- **SEO & Performance:** Totalmente otimizado para Core Web Vitals, com carregamento de imagens prioritário (LCP) e sem dependência de plugins pesados.
- **Design Moderno:** Interface "Glassmorphism" com suporte a modo escuro (Dark Mode) nativo.

## 🛠️ Tecnologias Utilizadas

- **WordPress Core** (Otimizado para PHP 8.2+)
- **Tailwind CSS 3.4+** (Sistema de Design Utilitário)
- **Material Symbols** (Ícones modernos do Google)
- **Vanilla JavaScript** (Zero dependência de jQuery para performance máxima)

## 📂 Estrutura do Projeto

- `/includes`: Lógica de PHP, CPTs e metaboxes customizados.
- `/template-parts`: Componentes reutilizáveis (Header, Footer, Widgets).
- `/assets`: Imagens globais e configurações de build.
- `functions.php`: O coração do tema com as injeções de filtros e hooks.

## ⚙️ Instalação e Desenvolvimento

1. Clone o repositório na pasta `/wp-content/themes/` do seu WordPress.
2. Instale as dependências caso queira rodar o build do Tailwind:
   ```bash
   npm install
   npm run build
   ```
3. Ative o tema no painel administrativo do WordPress.

---
**Desenvolvido por STS Digital (2026)**
