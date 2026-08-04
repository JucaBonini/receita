<?php
/**
 * Template Name: Contato
 * Template for contact page
 */
get_header(); ?>

<main class="contact-page">
    <!-- Hero Section do Contato -->
    <section class="contact-hero">
        <div class="container">
            <div class="contact-header">
                <h1 class="contact-title">Fale Conosco</h1>
                <p class="contact-subtitle">Estamos aqui para ajudar! Entre em contato conosco através dos canais abaixo ou preencha o formulário.</p>
            </div>
        </div>
    </section>

    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo home_url(); ?>">Home</a>
                <span class="separator">/</span>
                <span class="current">Contato</span>
            </nav>
        </div>
    </section>

    <div class="container">
        <div class="contact-content">
            <!-- Informações de Contato -->
            <section class="contact-info-section">
                <div class="contact-info-grid">
                    
                    <!-- E-mail -->
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3 class="contact-method-title">E-mail</h3>
                        <p class="contact-detail"><?php echo get_theme_mod('contact_email', 'contato@descomplicandoreceitas.com.br'); ?></p>
                        <span class="contact-availability">Respondemos em até 24h</span>
                    </div>

                    <!-- WhatsApp -->
                    <div class="contact-info-card">
                        <div class="contact-icon">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <h3 class="contact-method-title">Participe do nosso Canal no WhatsApp</h3>
                        <p class="contact-detail"><?php echo get_theme_mod('contact_whatsapp', 'Receba Receitas no WhatsApp!'); ?></p>
                        <a href="https://whatsapp.com/channel/0029Va5fCv1FXUuaQxDdVg0H<?php echo preg_replace('/\D/', '', get_theme_mod('contact_whatsapp', '11999999999')); ?>" 
                           class="btn btn-secondary whatsapp-btn" target="_blank">
                            <i class="fab fa-whatsapp"></i>
                           Participar
                        </a>
                    </div>

                </div>
            </section>

            <!-- Conteúdo Principal -->
            <div class="contact-main-content">
                <!-- Formulário de Contato -->
                <section class="contact-form-section">
                    <div class="form-header">
                        <h2 class="form-title">Envie sua Mensagem</h2>
                        <p class="form-description">Preencha o formulário abaixo e entraremos em contato o mais breve possível.</p>
                    </div>

                    <form class="contact-form" id="contactForm" method="POST">
                        <?php wp_nonce_field('contact_form', 'contact_nonce'); ?>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact-name" class="form-label">Nome Completo *</label>
                                <input type="text" id="contact-name" name="contact_name" class="form-input" required>
                                <span class="form-error" id="name-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="contact-email" class="form-label">E-mail *</label>
                                <input type="email" id="contact-email" name="contact_email" class="form-input" required>
                                <span class="form-error" id="email-error"></span>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact-phone" class="form-label">Telefone</label>
                                <input type="tel" id="contact-phone" name="contact_phone" class="form-input">
                                <span class="form-error" id="phone-error"></span>
                            </div>
                            <div class="form-group">
                                <label for="contact-subject" class="form-label">Assunto *</label>
                                <select id="contact-subject" name="contact_subject" class="form-select" required>
                                    <option value="">Selecione um assunto</option>
                                    <option value="duvida">Dúvida sobre Receitas</option>
                                    <option value="sugestao">Sugestão de Conteúdo</option>
                                    <option value="parceria">Proposta de Parceria</option>
                                    <option value="publicidade">Publicidade no Site</option>
                                    <option value="tecnico">Problema Técnico</option>
                                    <option value="outro">Outro</option>
                                </select>
                                <span class="form-error" id="subject-error"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="contact-message" class="form-label">Mensagem *</label>
                            <textarea id="contact-message" name="contact_message" class="form-textarea" rows="6" 
                                      placeholder="Descreva sua dúvida, sugestão ou mensagem..." required></textarea>
                            <div class="textarea-info">
                                <span class="char-count">0</span> / 500 caracteres
                            </div>
                            <span class="form-error" id="message-error"></span>
                        </div>

                        <!-- Google reCAPTCHA -->
                        <div class="form-group">
                            <div class="g-recaptcha" data-sitekey="<?php echo get_theme_mod('recaptcha_site_key', 'your-site-key'); ?>"></div>
                            <span class="form-error" id="captcha-error"></span>
                        </div>

                        <div class="form-submit">
                            <button type="submit" class="btn btn-submit" id="submitBtn">
                                <i class="fas fa-paper-plane"></i>
                                Enviar Mensagem
                            </button>
                            <div class="form-notice">
                                <i class="fas fa-info-circle"></i>
                                Campos marcados com * são obrigatórios
                            </div>
                        </div>

                        <div class="form-feedback" id="formFeedback"></div>
                    </form>
                </section>

                <!-- FAQ Section -->
                <section class="contact-faq-section">
                    <h2 class="faq-title">Perguntas Frequentes</h2>
                    <div class="faq-list">
                        <div class="faq-item">
                            <button class="faq-question">
                                <span>Quanto tempo leva para obter uma resposta?</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="faq-answer">
                                <p>Respondemos todos os e-mails em até 24 horas úteis. Para questões urgentes, recomendamos o contato via WhatsApp.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question">
                                <span>Posso sugerir uma receita para o site?</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="faq-answer">
                                <p>Sim! Adoramos receber sugestões de receitas. Envie sua ideia através do formulário selecionando "Sugestão de Conteúdo".</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question">
                                <span>Vocês fazem parcerias com marcas?</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="faq-answer">
                                <p>Sim, estamos abertos a parcerias. Entre em contato selecionando "Proposta de Parceria" e nossa equipe comercial retornará.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question">
                                <span>Como reportar um problema técnico no site?</span>
                                <i class="fas fa-chevron-down"></i>
                            </button>
                            <div class="faq-answer">
                                <p>Se encontrar algum problema, selecione "Problema Técnico" no formulário e descreva detalhadamente o ocorrido.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Sidebar -->
            <aside class="contact-sidebar">
               
                <!-- Redes Sociais -->
                <div class="sidebar-widget">
                    <h3 class="widget-title">Siga-nos</h3>
                    <div class="social-contact">
                        <a href="<?php echo get_theme_mod('facebook_url', 'https://www.facebook.com/descomplicandoreceitasofic/'); ?>" class="social-contact-link" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="<?php echo get_theme_mod('instagram_url', 'https://www.instagram.com/descomplicandoreceitasofic/'); ?>" class="social-contact-link" target="_blank">
                            <i class="fab fa-instagram"></i>
                            <span>Instagram</span>
                        </a>
                          <a href="<?php echo get_theme_mod('pinterest_url', 'https://br.pinterest.com/descomplicandoreceitas/'); ?>" class="social-contact-link" target="_blank">
                            <i class="fab fa-pinterest-p"></i>
                            <span>Pinterest</span>
                        </a>
                        <a href="<?php echo get_theme_mod('youtube_url', 'https://www.youtube.com/@descomplicandoreceitas'); ?>" class="social-contact-link" target="_blank">
                            <i class="fab fa-youtube"></i>
                            <span>YouTube</span>
                        </a>
                      
                    </div>
                </div>                
            </aside>
        </div>
    </div>
</main>

<style>
/* ESTILOS NOVOS APENAS - Contact específico */

/* Hero Section do Contato */
.contact-hero {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    padding: 80px 0 60px;
}

.contact-header {
    text-align: center;
    max-width: 600px;
    margin: 0 auto;
}

.contact-title {
    font-size: 3rem;
    margin-bottom: 20px;
    color: white;
}

.contact-subtitle {
    font-size: 1.3rem;
    opacity: 0.9;
    line-height: 1.5;
}

/* Contact Content */
.contact-content {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 50px;
    padding: 50px 0;
}

/* Contact Info Section */
.contact-info-section {
    grid-column: 1 / -1;
    margin-bottom: 40px;
}

.contact-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
}

.contact-info-card {
    background: white;
    padding: 30px;
    border-radius: var(--radius);
    text-align: center;
    box-shadow: var(--shadow);
    transition: var(--transition);
    border: 2px solid transparent;
}

.contact-info-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    border-color: var(--primary);
}

.contact-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    color: white;
    font-size: 1.5rem;
}

.contact-method-title {
    font-size: 1.3rem;
    margin-bottom: 10px;
    color: var(--dark);
}

.contact-detail {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 8px;
}

.contact-availability {
    font-size: 0.9rem;
    color: var(--gray);
    display: block;
}

.whatsapp-btn {
    margin-top: 15px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

/* Contact Main Content */
.contact-main-content {
    display: flex;
    flex-direction: column;
    gap: 50px;
}

/* Contact Form Section */
.contact-form-section {
    background: white;
    padding: 40px;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.form-header {
    text-align: center;
    margin-bottom: 30px;
}

.form-title {
    font-size: 2rem;
    margin-bottom: 10px;
    color: var(--dark);
}

.form-description {
    color: var(--gray);
    font-size: 1.1rem;
}

/* Form Styles */
.contact-form {
    max-width: 100%;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark);
}

.form-label::after {
    content: '*';
    color: var(--primary);
    margin-left: 4px;
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: 12px 15px;
    border: 2px solid var(--light);
    border-radius: var(--radius);
    font-size: 1rem;
    transition: var(--transition);
    background: white;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1);
}

.form-textarea {
    resize: vertical;
    min-height: 120px;
}

.textarea-info {
    display: flex;
    justify-content: space-between;
    margin-top: 5px;
    font-size: 0.85rem;
    color: var(--gray);
}

.char-count {
    font-weight: 600;
}

.form-error {
    display: block;
    color: #e74c3c;
    font-size: 0.85rem;
    margin-top: 5px;
    min-height: 20px;
}

/* reCAPTCHA */
.g-recaptcha {
    margin: 15px 0;
}

/* Form Submit */
.form-submit {
    text-align: center;
    margin-top: 30px;
}

.btn-submit {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    padding: 15px 40px;
    font-size: 1.1rem;
    border: none;
    transition: var(--transition);
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
}

.btn-submit:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.form-notice {
    margin-top: 15px;
    font-size: 0.9rem;
    color: var(--gray);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

/* Form Feedback */
.form-feedback {
    margin-top: 20px;
    padding: 15px;
    border-radius: var(--radius);
    text-align: center;
    font-weight: 600;
    display: none;
}

.form-feedback.success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    display: block;
}

.form-feedback.error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    display: block;
}

/* FAQ Section */
.contact-faq-section {
    background: white;
    padding: 40px;
    border-radius: var(--radius);
    box-shadow: var(--shadow);
}

.faq-title {
    font-size: 2rem;
    margin-bottom: 30px;
    color: var(--dark);
    text-align: center;
}

.faq-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.faq-item {
    border: 2px solid var(--light);
    border-radius: var(--radius);
    overflow: hidden;
}

.faq-question {
    width: 100%;
    padding: 20px 25px;
    background: white;
    border: none;
    text-align: left;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--dark);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: var(--transition);
}

.faq-question:hover {
    background: #f8f9fa;
}

.faq-question i {
    transition: var(--transition);
    color: var(--gray);
}

.faq-item.active .faq-question i {
    transform: rotate(180deg);
    color: var(--primary);
}

.faq-answer {
    padding: 0 25px;
    max-height: 0;
    overflow: hidden;
    transition: var(--transition);
    background: #f8f9fa;
}

.faq-item.active .faq-answer {
    padding: 20px 25px;
    max-height: 200px;
}

.faq-answer p {
    margin: 0;
    line-height: 1.6;
    color: var(--dark);
}

/* Contact Sidebar */
.contact-sidebar {
    position: sticky;
    top: 100px;
    height: fit-content;
}

/* Business Hours */
.business-hours {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.hour-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid var(--light);
}

.hour-item:last-child {
    border-bottom: none;
}

.day {
    font-weight: 500;
    color: var(--dark);
}

.time {
    color: var(--gray);
    font-weight: 600;
}

.time.closed {
    color: #e74c3c;
}

/* Social Contact */
.social-contact {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.social-contact-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 15px;
    background: #f8f9fa;
    border-radius: var(--radius);
    color: var(--dark);
    text-decoration: none;
    transition: var(--transition);
    font-weight: 500;
}

.social-contact-link:hover {
    background: var(--primary);
    color: white;
    transform: translateX(5px);
}

.social-contact-link i {
    width: 20px;
    text-align: center;
    font-size: 1.1rem;
}

/* Contact Map */
.contact-map {
    background: #f8f9fa;
    border-radius: var(--radius);
    overflow: hidden;
}

.map-placeholder {
    padding: 40px 20px;
    text-align: center;
    color: var(--gray);
}

.map-placeholder i {
    font-size: 3rem;
    margin-bottom: 15px;
    color: var(--primary);
}

.map-placeholder p {
    margin-bottom: 20px;
    font-weight: 600;
}

/* Responsividade Contact */
@media (max-width: 992px) {
    .contact-content {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .contact-sidebar {
        position: static;
    }
    
    .contact-title {
        font-size: 2.5rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
}

@media (max-width: 768px) {
    .contact-hero {
        padding: 60px 0 40px;
    }
    
    .contact-title {
        font-size: 2rem;
    }
    
    .contact-subtitle {
        font-size: 1.1rem;
    }
    
    .contact-form-section,
    .contact-faq-section {
        padding: 30px 25px;
    }
    
    .contact-info-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .contact-title {
        font-size: 1.8rem;
    }
    
    .contact-form-section,
    .contact-faq-section {
        padding: 25px 20px;
    }
    
    .form-title,
    .faq-title {
        font-size: 1.5rem;
    }
    
    .contact-info-card {
        padding: 25px 20px;
    }
}
</style>

<script>
// Formulário de Contato
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const messageTextarea = document.getElementById('contact-message');
    const charCount = document.querySelector('.char-count');
    const submitBtn = document.getElementById('submitBtn');
    const formFeedback = document.getElementById('formFeedback');

    // Contador de caracteres
    if (messageTextarea && charCount) {
        messageTextarea.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count > 500) {
                charCount.style.color = '#e74c3c';
            } else {
                charCount.style.color = 'inherit';
            }
        });
    }

    // FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.parentElement;
            const isActive = faqItem.classList.contains('active');
            
            // Fechar todos os itens
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Abrir o item clicado se não estava ativo
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });

    // Validação do formulário
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateForm()) {
                submitForm();
            }
        });
    }

    function validateForm() {
        let isValid = true;
        const fields = [
            { id: 'contact-name', errorId: 'name-error', validator: validateName },
            { id: 'contact-email', errorId: 'email-error', validator: validateEmail },
            { id: 'contact-phone', errorId: 'phone-error', validator: validatePhone },
            { id: 'contact-subject', errorId: 'subject-error', validator: validateSubject },
            { id: 'contact-message', errorId: 'message-error', validator: validateMessage }
        ];

        fields.forEach(field => {
            const element = document.getElementById(field.id);
            const errorElement = document.getElementById(field.errorId);
            const value = element ? element.value.trim() : '';
            
            if (field.validator(value, element)) {
                if (errorElement) errorElement.textContent = '';
                if (element) element.style.borderColor = '';
            } else {
                isValid = false;
                if (element) element.style.borderColor = '#e74c3c';
            }
        });

        return isValid;
    }

    function validateName(name) {
        if (!name) return 'Nome é obrigatório';
        if (name.length < 2) return 'Nome deve ter pelo menos 2 caracteres';
        return true;
    }

    function validateEmail(email) {
        if (!email) return 'E-mail é obrigatório';
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) return 'E-mail inválido';
        return true;
    }

    function validatePhone(phone) {
        if (phone && phone.replace(/\D/g, '').length < 10) {
            return 'Telefone inválido';
        }
        return true;
    }

    function validateSubject(subject) {
        if (!subject) return 'Assunto é obrigatório';
        return true;
    }

    function validateMessage(message) {
        if (!message) return 'Mensagem é obrigatória';
        if (message.length < 10) return 'Mensagem muito curta';
        if (message.length > 500) return 'Mensagem muito longa';
        return true;
    }

    function submitForm() {
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
        }

        const formData = new FormData(contactForm);
        
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showFeedback('Mensagem enviada com sucesso! Entraremos em contato em breve.', 'success');
                contactForm.reset();
                if (charCount) charCount.textContent = '0';
            } else {
                showFeedback('Erro ao enviar mensagem. Tente novamente.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFeedback('Erro ao enviar mensagem. Tente novamente.', 'error');
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Mensagem';
            }
        });
    }

    function showFeedback(message, type) {
        if (formFeedback) {
            formFeedback.textContent = message;
            formFeedback.className = `form-feedback ${type}`;
            formFeedback.style.display = 'block';
            
            setTimeout(() => {
                formFeedback.style.display = 'none';
            }, 5000);
        }
    }
});

// Newsletter form
document.getElementById('contact-newsletter-form')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const email = this.querySelector('input[type="email"]').value;
    
    fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'action=newsletter_subscribe&email=' + encodeURIComponent(email)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Obrigado por se inscrever! Você receberá nossas novidades em breve.');
            this.reset();
        } else {
            alert('Erro ao se inscrever. Tente novamente.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao se inscrever. Tente novamente.');
    });
});
</script>

<!-- Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<?php get_footer(); ?>