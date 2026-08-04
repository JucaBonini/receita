<?php
/**
 * Header específico para a página de cardápio
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?> - Cardápio da Semana</title>
    <?php wp_head(); ?>
    <style>
:root {
    --primary: #e74c3c;
    --primary-dark: #c0392b;
    --secondary: #2ecc71;
    --dark: #2c3e50;
    --light: #ecf0f1;
    --gray: #95a5a6;
    --border: #bdc3c7;
    --spacing-sm: 8px;
    --spacing-md: 16px;
    --spacing-lg: 24px;
    --spacing-xl: 32px;
    --radius: 8px;
    --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.15);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
    font-size: 16px;
    line-height: 1.6;
    color: var(--dark);
    background-color: #f8f9fa;
    padding-bottom: 80px;
}

/* ============================================================
   CONTAINER E LAYOUT
   ============================================================ */

.container {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 var(--spacing-md);
}

/* ============================================================
   HEADER
   ============================================================ */

.app-header {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    padding: var(--spacing-lg) 0;
    box-shadow: var(--shadow);
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: var(--spacing-lg);
}

.app-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: var(--spacing-sm);
}

.app-subtitle {
    font-size: 0.9rem;
    opacity: 0.9;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: var(--spacing-sm);
}

.header-btn {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.header-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

/* ============================================================
   WEEK SELECTOR
   ============================================================ */

.week-selector {
    background: white;
    padding: var(--spacing-lg) 0;
    border-bottom: 1px solid var(--border);
    margin-bottom: var(--spacing-lg);
}

.week-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--spacing-md);
}

.week-btn {
    background: var(--primary);
    color: white;
    border: none;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.week-btn:hover:not(:disabled) {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.week-btn:disabled {
    background: var(--gray);
    cursor: not-allowed;
    opacity: 0.5;
}

.current-week {
    flex: 1;
    text-align: center;
}

.week-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--dark);
    margin-bottom: var(--spacing-sm);
}

.week-dates {
    font-size: 0.9rem;
    color: var(--gray);
    margin: 0;
}

/* ============================================================
   MEAL PLAN
   ============================================================ */

.meal-plan-container {
    margin-bottom: var(--spacing-xl);
}

.entry-content {
    margin-bottom: var(--spacing-xl);
    background: white;
    padding: var(--spacing-lg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.entry-content p {
    margin-bottom: var(--spacing-md);
}

.cardapio-meals {
    margin-bottom: var(--spacing-xl);
}

.day-card {
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    margin-bottom: var(--spacing-lg);
    overflow: hidden;
    transition: all 0.3s ease;
}

.day-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.day-card.active {
    border-left: 4px solid var(--secondary);
}

.day-header {
    background: linear-gradient(135deg, var(--dark), #34495e);
    color: white;
    padding: var(--spacing-md);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: var(--spacing-md);
}

.day-info {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.day-number {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.3rem;
}

.day-name {
    font-size: 1.1rem;
    font-weight: 600;
}

.day-date {
    font-size: 0.85rem;
    opacity: 0.8;
}

.day-status {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
}

.status-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--gray);
}

.status-dot.completed {
    background: var(--secondary);
}

/* ============================================================
   MEAL SECTIONS
   ============================================================ */

.meal-section {
    padding: var(--spacing-md);
    border-bottom: 1px solid #eee;
}

.meal-section:last-child {
    border-bottom: none;
}

.meal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-md);
}

.meal-title {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    font-size: 1rem;
    font-weight: 600;
    color: var(--dark);
}

.meal-icon {
    color: var(--primary);
}

.meal-time {
    font-size: 0.85rem;
    color: var(--gray);
    background: #f0f0f0;
    padding: 4px 8px;
    border-radius: 4px;
}

.meal-content {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.meal-image {
    width: 100%;
    height: 200px;
    border-radius: var(--radius);
    overflow: hidden;
    background: #f0f0f0;
}

.meal-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.meal-content:hover .meal-image img {
    transform: scale(1.05);
}

.meal-details {
    flex: 1;
}

.meal-name {
    font-size: 1rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: var(--spacing-sm);
}

.meal-description {
    font-size: 0.9rem;
    color: var(--gray);
    margin-bottom: var(--spacing-sm);
    line-height: 1.5;
}

.meal-tags {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
}

.meal-tag {
    display: inline-block;
    padding: 4px 12px;
    background: #f0f0f0;
    color: var(--dark);
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 500;
}

.meal-tag.quick {
    background: #e8f8f5;
    color: var(--secondary);
}

.meal-tag.economical {
    background: #fef5e7;
    color: #f39c12;
}

.meal-tag.healthy {
    background: #e8f8f5;
    color: var(--secondary);
}

/* ============================================================
   STATISTICS
   ============================================================ */

.stats-card {
    background: white;
    padding: var(--spacing-lg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    margin-bottom: var(--spacing-xl);
}

.stats-card h3 {
    font-size: 1.2rem;
    margin-bottom: var(--spacing-lg);
    color: var(--dark);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: var(--spacing-md);
}

.stat-item {
    background: linear-gradient(135deg, #f8f9fa, #ecf0f1);
    padding: var(--spacing-md);
    border-radius: var(--radius);
    text-align: center;
    transition: all 0.3s ease;
}

.stat-item:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow);
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: var(--spacing-sm);
}

.stat-label {
    font-size: 0.85rem;
    color: var(--gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ============================================================
   SHOPPING PREVIEW
   ============================================================ */

.shopping-preview {
    background: white;
    padding: var(--spacing-lg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    margin-bottom: var(--spacing-xl);
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-lg);
    gap: var(--spacing-md);
}

.preview-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
}

.preview-items {
    display: flex;
    flex-wrap: wrap;
    gap: var(--spacing-sm);
}

.preview-item {
    background: var(--primary);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

/* ============================================================
   BUTTONS
   ============================================================ */

.btn {
    display: inline-block;
    padding: 12px 24px;
    background: var(--primary);
    color: white;
    border: none;
    border-radius: var(--radius);
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    text-align: center;
}

.btn:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: var(--shadow);
}

.btn-secondary {
    background: var(--gray);
}

.btn-secondary:hover {
    background: #7f8c8d;
}

.btn-read-more {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

/* ============================================================
   MODALS
   ============================================================ */

.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-md);
}

.modal-overlay.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: var(--radius);
    max-width: 600px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: var(--shadow-lg);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: var(--spacing-lg);
    border-bottom: 1px solid var(--border);
}

.modal-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: var(--dark);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--gray);
    transition: color 0.3s ease;
}

.modal-close:hover {
    color: var(--dark);
}

.modal-body {
    padding: var(--spacing-lg);
}

/* ============================================================
   ADVERTISEMENTS
   ============================================================ */

.cardapio-advertisement {
    background: white;
    padding: var(--spacing-lg);
    border-radius: var(--radius);
    box-shadow: var(--shadow);
    margin-bottom: var(--spacing-xl);
    border: 1px solid #e0e0e0;
    position: relative;
}

.cardapio-advertisement-label {
    position: absolute;
    top: 8px;
    right: 8px;
    font-size: 0.7rem;
    color: var(--gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

/* ============================================================
   QUICK ACTIONS
   ============================================================ */

.quick-actions {
    position: fixed;
    right: 20px;
    bottom: 100px;
    z-index: 500;
}

.action-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--primary);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    box-shadow: var(--shadow-lg);
    transition: all 0.3s ease;
}

.action-btn:hover {
    background: var(--primary-dark);
    transform: scale(1.1);
}

/* ============================================================
   BOTTOM NAVIGATION
   ============================================================ */

.bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    border-top: 1px solid var(--border);
    display: flex;
    justify-content: space-around;
    z-index: 100;
    box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
}

.nav-item {
    flex: 1;
    padding: 8px 0;
    text-align: center;
    color: var(--gray);
    text-decoration: none;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 60px;
}

.nav-item:hover,
.nav-item.active {
    color: var(--primary);
}

.nav-icon {
    font-size: 1.3rem;
    margin-bottom: 2px;
}

.nav-label {
    font-size: 0.7rem;
    font-weight: 500;
}

/* ============================================================
   EMPTY STATE
   ============================================================ */

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: var(--spacing-md);
}

.empty-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: var(--dark);
    margin-bottom: var(--spacing-sm);
}

.empty-text {
    color: var(--gray);
    margin-bottom: var(--spacing-lg);
}

/* ============================================================
   LOADING STATE
   ============================================================ */

.loading-shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        background-position: 200% 0;
    }
    100% {
        background-position: -200% 0;
    }
}

/* ============================================================
   UTILITIES
   ============================================================ */

.screen-reader-text {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}

/* ============================================================
   PRINT STYLES
   ============================================================ */

@media print {
    body {
        padding-bottom: 0;
    }
    
    .app-header,
    .bottom-nav,
    .quick-actions,
    .header-actions,
    .week-nav,
    .modal-overlay {
        display: none !important;
    }
    
    .day-card {
        page-break-inside: avoid;
        box-shadow: none;
        border: 1px solid #ddd;
        margin-bottom: 20px;
    }
    
    .meal-image {
        display: none;
    }
    
    a {
        color: var(--dark);
        text-decoration: underline;
    }
}

@media (prefers-color-scheme: dark) {
    :root {
        --dark: #ecf0f1;
        --light: #2c3e50;
    }
    
    body {
        background-color: #1a1a1a;
        color: #ecf0f1;
    }
    
    .day-card,
    .stats-card,
    .shopping-preview,
    .modal-content,
    .empty-state,
    .entry-content {
        background-color: #2c3e50;
        color: #ecf0f1;
    }
    
    .day-header {
        background: linear-gradient(135deg, #34495e, #2c3e50);
    }
    
    .modal-overlay {
        background: rgba(0, 0, 0, 0.8);
    }
    
    .bottom-nav {
        background: #2c3e50;
        border-top-color: #34495e;
    }
}
@media (min-width: 768px) {
    .container {
        padding: 0 24px;
    }
    
    h1 {
        font-size: 2rem;
    }
    
    h2 {
        font-size: 1.75rem;
    }
    
    h3 {
        font-size: 1.5rem;
    }
    
    .app-title {
        font-size: 1.75rem;
    }
    
    .week-title {
        font-size: 1.3rem;
    }
    
    .meal-content {
        flex-direction: row;
        gap: 20px;
    }
    
    .meal-image {
        width: 120px;
        height: 120px;
        flex-shrink: 0;
    }
    
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    
    .day-header {
        flex-direction: row;
        align-items: center;
    }
    
    .day-status {
        margin-left: auto;
    }
}

/* ============================================================
   DESKTOP (1024px and up)
   ============================================================ */

@media (min-width: 1024px) {
    .container {
        padding: 0 32px;
        max-width: 1280px;
    }
    
    .app-header {
        padding: 20px 0;
    }
    
    .app-title {
        font-size: 2rem;
    }
    
    .app-subtitle {
        font-size: 1rem;
    }
    
    .week-selector {
        padding: 20px 0;
    }
    
    .week-title {
        font-size: 1.5rem;
    }
    
    .meal-plan-container {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 30px;
    }
    
    .cardapio-meals {
        grid-column: 1;
    }
    
    .day-card {
        margin-bottom: 0;
    }
    
    .meal-image {
        width: 150px;
        height: 150px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .shopping-preview {
        margin-top: 30px;
    }
    
    .quick-actions {
        right: 40px;
        bottom: 120px;
    }
}

/* ============================================================
   LARGE DESKTOP (1440px and up)
   ============================================================ */

@media (min-width: 1440px) {
    .container {
        max-width: 1400px;
    }
}

/* ============================================================
   SMALL DEVICES (480px and down)
   ============================================================ */

@media (max-width: 480px) {
    :root {
        --spacing-md: 12px;
        --spacing-lg: 16px;
    }
    
    .container {
        padding: 0 12px;
    }
    
    h1 {
        font-size: 1.5rem;
    }
    
    h2 {
        font-size: 1.25rem;
    }
    
    h3 {
        font-size: 1.1rem;
    }
    
    .app-header {
        padding: 10px 0;
    }
    
    .app-title {
        font-size: 1.1rem;
    }
    
    .app-subtitle {
        font-size: 0.8rem;
    }
    
    .header-btn {
        width: 36px;
        height: 36px;
        font-size: 1rem;
    }
    
    .week-selector {
        padding: 12px 0;
    }
    
    .week-nav {
        gap: 8px;
    }
    
    .week-btn {
        width: 36px;
        height: 36px;
        font-size: 1rem;
    }
    
    .week-title {
        font-size: 1rem;
    }
    
    .week-dates {
        font-size: 0.8rem;
    }
    
    .day-card {
        margin-bottom: 12px;
    }
    
    .day-header {
        padding: 12px;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }
    
    .day-info {
        gap: 8px;
    }
    
    .day-number {
        width: 36px;
        height: 36px;
        font-size: 1.2rem;
    }
    
    .day-name {
        font-size: 1rem;
    }
    
    .day-status {
        align-self: flex-end;
    }
    
    .meal-section {
        padding: 12px;
    }
    
    .meal-header {
        margin-bottom: 8px;
    }
    
    .meal-title {
        font-size: 1rem;
        gap: 6px;
    }
    
    .meal-icon {
        font-size: 1rem;
    }
    
    .meal-content {
        flex-direction: column;
        gap: 8px;
    }
    
    .meal-image {
        width: 100%;
        height: 150px;
    }
    
    .meal-name {
        font-size: 0.95rem;
    }
    
    .meal-description {
        font-size: 0.85rem;
    }
    
    .meal-tag {
        font-size: 0.65rem;
        padding: 2px 6px;
    }
    
    .stats-card {
        padding: 16px;
        margin-top: 16px;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .stat-item {
        padding: 12px;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
    
    .stat-label {
        font-size: 0.7rem;
    }
    
    .shopping-preview {
        padding: 16px;
        margin-top: 16px;
    }
    
    .preview-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }
    
    .preview-title {
        font-size: 1rem;
    }
    
    .preview-items {
        gap: 6px;
    }
    
    .preview-item {
        padding: 4px 8px;
        font-size: 0.75rem;
    }
    
    .btn {
        padding: 10px 16px;
        font-size: 0.9rem;
    }
    
    .action-btn {
        width: 56px;
        height: 56px;
        font-size: 1.1rem;
    }
    
    .quick-actions {
        right: 16px;
        bottom: 100px;
    }
    
    .bottom-nav {
        padding: 8px 0;
    }
    
    .nav-item {
        padding: 6px 0;
    }
    
    .nav-icon {
        font-size: 1rem;
        margin-bottom: 2px;
    }
    
    .nav-label {
        font-size: 0.6rem;
    }
    
    .modal-content {
        max-width: 95vw;
    }
    
    .modal-header {
        padding: 16px;
    }
    
    .modal-body {
        padding: 16px;
    }
    
    .empty-state {
        padding: 30px 12px;
    }
    
    .empty-icon {
        font-size: 2.5rem;
    }
    
    .empty-title {
        font-size: 1.1rem;
    }
    
    .empty-text {
        font-size: 0.9rem;
    }
}

/* ============================================================
   LANDSCAPE MODE (Height < 500px)
   ============================================================ */

@media (max-height: 500px) and (orientation: landscape) {
    body {
        padding-bottom: 60px;
    }
    
    .app-header {
        padding: 8px 0;
    }
    
    .week-selector {
        padding: 8px 0;
        margin-bottom: 8px;
    }
    
    .day-card {
        margin-bottom: 8px;
    }
    
    .meal-section {
        padding: 8px;
    }
    
    .bottom-nav {
        padding: 6px 0;
    }
}

/* ============================================================
   REDUCED MOTION
   ============================================================ */

@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
    }
}

/* ============================================================
   HIGH CONTRAST MODE
   ============================================================ */

@media (prefers-contrast: more) {
    .btn {
        border: 2px solid var(--primary);
    }
    
    .day-card {
        border: 2px solid var(--dark);
    }
    
    .meal-tag {
        border: 1px solid currentColor;
    }
}

/* ============================================================
   TOUCH DEVICE OPTIMIZATIONS
   ============================================================ */

@media (hover: none) and (pointer: coarse) {
    .btn,
    .action-btn,
    .week-btn,
    .header-btn,
    .nav-item {
        min-height: 44px;
        min-width: 44px;
    }
    
    .btn {
        padding: 14px 28px;
    }
    
    .day-card:hover {
        transform: none;
    }
    
    .day-card:active {
        transform: translateY(-2px);
    }
}
    </style>
</head>
<body <?php body_class('cardapio-page'); ?>>
    <!-- App Header -->
    <header class="app-header">
        <div class="container header-content">
            <div>
                <h1 class="app-title">Cardápio da Semana</h1>
                <p class="app-subtitle">Receitas práticas e não repetitivas</p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="home-link">
                    ← Voltar para o site principal
                </a>
            </div>
            <div class="header-actions">
                <button class="header-btn" id="printBtn" title="Imprimir cardápio">
                    <i class="fas fa-print"></i>
                </button>
            </div>
        </div>
    </header>