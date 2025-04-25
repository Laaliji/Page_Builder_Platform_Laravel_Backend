<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Landing Page Template
        Template::create([
            'title' => 'Landing Page',
            'description' => 'Modèle de landing page : mise en page prédéfinie pour la visualisation.',
            'preview_image_url' => '/assets/templates/blank.png',
            'html_content' => $this->getLandingPageHTML(),
            'css_content' => $this->getLandingPageCSS(),
        ]);

        // 2. Dashboard Template
        Template::create([
            'title' => 'Tableau de Bord',
            'description' => 'Modèle de tableau de bord : mise en page prédéfinie pour la visualisation.',
            'preview_image_url' => '/assets/templates/dashboard.png',
            'html_content' => $this->getDashboardHTML(),
            'css_content' => $this->getDashboardCSS(),
        ]);

        // 3. Minimalist Template
        Template::create([
            'title' => 'Minimaliste',
            'description' => 'Modèle minimaliste : conception élégante et simple pour un look moderne.',
            'preview_image_url' => '/assets/templates/minimal.png',
            'html_content' => $this->getMinimalistHTML(),
            'css_content' => $this->getMinimalistCSS(),
        ]);
    }

    /**
     * Get Landing Page HTML
     */
    private function getLandingPageHTML(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page Template</title>
</head>
<body>
    <!-- Header -->
    <header class="hero">
        <nav class="navbar">
            <div class="logo">VotreLogo</div>
            <div class="nav-links">
                <a href="#features">Fonctionnalités</a>
                <a href="#pricing">Tarifs</a>
                <a href="#contact">Contact</a>
                <a href="#" class="btn-primary">S'inscrire</a>
            </div>
        </nav>
        
        <div class="hero-content">
            <h1>Solution parfaite pour votre entreprise</h1>
            <p>Découvrez comment notre plateforme peut vous aider à atteindre vos objectifs.</p>
            <div class="cta-buttons">
                <a href="#" class="btn-primary">Commencer</a>
                <a href="#" class="btn-secondary">En savoir plus</a>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section id="features" class="features">
        <h2>Nos fonctionnalités</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Analyses avancées</h3>
                <p>Comprenez vos données comme jamais auparavant avec nos outils d'analyse.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Sécurité renforcée</h3>
                <p>Vos données sont protégées par des protocoles de sécurité de pointe.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🚀</div>
                <h3>Performance optimisée</h3>
                <p>Rapidité et efficacité pour une meilleure expérience utilisateur.</p>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials">
        <h2>Ce que disent nos clients</h2>
        <div class="testimonial-slider">
            <div class="testimonial-card">
                <p>"Ce service a transformé notre façon de travailler. Hautement recommandé!"</p>
                <div class="client-info">
                    <div class="client-name">Marie Dupont</div>
                    <div class="client-position">Directrice Marketing, TechCorp</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <h2>Prêt à commencer?</h2>
        <p>Rejoignez des milliers d'entreprises qui nous font confiance.</p>
        <a href="#" class="btn-primary">Démarrer gratuitement</a>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-logo">VotreLogo</div>
            <div class="footer-links">
                <div class="footer-column">
                    <h4>Produit</h4>
                    <a href="#">Fonctionnalités</a>
                    <a href="#">Tarifs</a>
                    <a href="#">FAQ</a>
                </div>
                <div class="footer-column">
                    <h4>Entreprise</h4>
                    <a href="#">À propos</a>
                    <a href="#">Blog</a>
                    <a href="#">Carrières</a>
                </div>
                <div class="footer-column">
                    <h4>Légal</h4>
                    <a href="#">Confidentialité</a>
                    <a href="#">Conditions</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 VotreEntreprise. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
HTML;
    }

    /**
     * Get Landing Page CSS
     */
    private function getLandingPageCSS(): string
    {
        return <<<'CSS'
/* General styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}

body {
    line-height: 1.6;
    color: #333;
}

h1, h2, h3, h4 {
    margin-bottom: 1rem;
    font-weight: 700;
    line-height: 1.2;
}

h1 {
    font-size: 3rem;
}

h2 {
    font-size: 2.5rem;
    text-align: center;
    margin-bottom: 2rem;
}

h3 {
    font-size: 1.5rem;
}

p {
    margin-bottom: 1.5rem;
}

a {
    text-decoration: none;
    color: inherit;
}

section {
    padding: 5rem 2rem;
}

/* Buttons */
.btn-primary {
    background-color: #3B82F6;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 0.375rem;
    font-weight: 600;
    display: inline-block;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #2563EB;
}

.btn-secondary {
    background-color: transparent;
    color: #3B82F6;
    border: 1px solid #3B82F6;
    padding: 0.75rem 1.5rem;
    border-radius: 0.375rem;
    font-weight: 600;
    display: inline-block;
    margin-left: 1rem;
    transition: all 0.3s ease;
}

.btn-secondary:hover {
    background-color: #EFF6FF;
}

/* Navigation */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
}

.logo {
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
}

.nav-links {
    display: flex;
    gap: 2rem;
    align-items: center;
}

.nav-links a {
    color: white;
    font-weight: 500;
}

/* Hero Section */
.hero {
    background: linear-gradient(to right, #2563EB, #3B82F6);
    color: white;
    padding-bottom: 5rem;
}

.hero-content {
    max-width: 800px;
    margin: 0 auto;
    padding: 4rem 2rem;
    text-align: center;
}

.hero-content h1 {
    margin-bottom: 1.5rem;
}

.hero-content p {
    font-size: 1.25rem;
    margin-bottom: 2.5rem;
}

.cta-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
}

/* Features Section */
.features {
    background-color: #f9fafb;
}

.feature-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.feature-card {
    background-color: white;
    padding: 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-5px);
}

.feature-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
}

/* Testimonials */
.testimonials {
    background-color: #fff;
}

.testimonial-slider {
    max-width: 800px;
    margin: 0 auto;
}

.testimonial-card {
    background-color: #f9fafb;
    padding: 2rem;
    border-radius: 0.5rem;
    text-align: center;
}

.testimonial-card p {
    font-size: 1.25rem;
    font-style: italic;
    position: relative;
}

.testimonial-card p::before {
    content: '"';
    font-size: 3rem;
    position: absolute;
    left: -1rem;
    top: -1rem;
    opacity: 0.2;
}

.client-info {
    margin-top: 1.5rem;
}

.client-name {
    font-weight: 700;
}

/* CTA Section */
.cta-section {
    background-color: #3B82F6;
    color: white;
    text-align: center;
    padding: 4rem 2rem;
}

.cta-section h2 {
    color: white;
}

.cta-section .btn-primary {
    background-color: white;
    color: #3B82F6;
    margin-top: 1rem;
}

.cta-section .btn-primary:hover {
    background-color: #f9fafb;
}

/* Footer */
footer {
    background-color: #1F2937;
    color: white;
    padding: 4rem 2rem 2rem;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    margin-bottom: 3rem;
}

.footer-logo {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 2rem;
}

.footer-links {
    display: flex;
    flex-wrap: wrap;
    gap: 4rem;
}

.footer-column h4 {
    color: white;
    margin-bottom: 1.5rem;
}

.footer-column a {
    display: block;
    margin-bottom: 0.75rem;
    color: #D1D5DB;
}

.footer-column a:hover {
    color: white;
}

.footer-bottom {
    text-align: center;
    padding-top: 2rem;
    border-top: 1px solid #374151;
    color: #9CA3AF;
}

/* Responsive Styles */
@media (max-width: 768px) {
    h1 {
        font-size: 2.5rem;
    }
    
    h2 {
        font-size: 2rem;
    }
    
    .navbar {
        flex-direction: column;
        gap: 1rem;
    }
    
    .nav-links {
        flex-direction: column;
        gap: 1rem;
    }
    
    .cta-buttons {
        flex-direction: column;
    }
    
    .btn-secondary {
        margin-left: 0;
        margin-top: 1rem;
    }
    
    .footer-content {
        flex-direction: column;
    }
    
    .footer-links {
        flex-direction: column;
        gap: 2rem;
    }
}
CSS;
    }

    /**
     * Get Dashboard HTML
     */
    private function getDashboardHTML(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Template</title>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">AdminPanel</div>
            </div>
            
            <nav class="sidebar-nav">
                <ul>
                    <li class="active">
                        <a href="#">
                            <span class="icon">📊</span>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon">👥</span>
                            <span>Utilisateurs</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon">📝</span>
                            <span>Rapports</span>
                        </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="icon">⚙️</span>
                            <span>Paramètres</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Navigation -->
            <header class="top-nav">
                <div class="search-bar">
                    <input type="text" placeholder="Rechercher...">
                </div>
                
                <div class="user-actions">
                    <div class="notifications">
                        <div class="icon">🔔</div>
                        <div class="badge">3</div>
                    </div>
                    <div class="user-profile">
                        <div class="avatar">👤</div>
                        <div class="user-info">
                            <div class="username">Jean Dupont</div>
                            <div class="role">Administrateur</div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <div class="page-header">
                    <h1>Tableau de bord</h1>
                    <div class="date-range">
                        <span>Aujourd'hui: 25 Avril 2025</span>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-title">Utilisateurs</div>
                        <div class="stat-value">2,546</div>
                        <div class="stat-change positive">+12.5%</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-title">Revenus</div>
                        <div class="stat-value">28,450 €</div>
                        <div class="stat-change positive">+5.2%</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-title">Commandes</div>
                        <div class="stat-value">1,250</div>
                        <div class="stat-change negative">-3.1%</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-title">Conversions</div>
                        <div class="stat-value">15.3%</div>
                        <div class="stat-change positive">+2.4%</div>
                    </div>
                </div>
                
                <!-- Charts Section -->
                <div class="charts-section">
                    <div class="chart-container large">
                        <div class="chart-header">
                            <h3>Tendance des ventes</h3>
                            <div class="chart-actions">
                                <select>
                                    <option>7 derniers jours</option>
                                    <option>30 derniers jours</option>
                                    <option>Année en cours</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-placeholder">
                            [Graphique des ventes]
                        </div>
                    </div>
                    
                    <div class="chart-container small">
                        <div class="chart-header">
                            <h3>Répartition des sources</h3>
                        </div>
                        <div class="chart-placeholder">
                            [Graphique circulaire]
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="recent-activity">
                    <div class="section-header">
                        <h3>Activité récente</h3>
                        <a href="#">Voir tout</a>
                    </div>
                    
                    <div class="activity-list">
                        <div class="activity-item">
                            <div class="activity-icon green">✓</div>
                            <div class="activity-details">
                                <div class="activity-desc">Nouvelle commande #12345 créée</div>
                                <div class="activity-time">Il y a 10 minutes</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon blue">👤</div>
                            <div class="activity-details">
                                <div class="activity-desc">Nouvel utilisateur inscrit: Sophie Martin</div>
                                <div class="activity-time">Il y a 2 heures</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon yellow">⚠️</div>
                            <div class="activity-details">
                                <div class="activity-desc">Stock faible pour le produit XYZ</div>
                                <div class="activity-time">Il y a 5 heures</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon red">❌</div>
                            <div class="activity-details">
                                <div class="activity-desc">Paiement échoué pour la commande #12340</div>
                                <div class="activity-time">Il y a 8 heures</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Get Dashboard CSS
     */
    private function getDashboardCSS(): string
    {
        return <<<'CSS'
/* General styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}

body {
    background-color: #F9FAFB;
    color: #1F2937;
    line-height: 1.5;
}

/* Dashboard Layout */
.dashboard-container {
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.sidebar {
    width: 250px;
    background-color: #1F2937;
    color: white;
    position: fixed;
    height: 100%;
    overflow-y: auto;
}

.sidebar-header {
    padding: 1.5rem;
    border-bottom: 1px solid #374151;
}

.logo {
    font-size: 1.25rem;
    font-weight: 700;
}

.sidebar-nav ul {
    list-style: none;
    padding: 1rem 0;
}

.sidebar-nav li {
    margin-bottom: 0.5rem;
}

.sidebar-nav li a {
    display: flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    color: #D1D5DB;
    text-decoration: none;
    transition: all 0.3s ease;
}

.sidebar-nav li.active a,
.sidebar-nav li a:hover {
    background-color: #374151;
    color: white;
}

.sidebar-nav .icon {
    margin-right: 0.75rem;
    font-size: 1.25rem;
}

/* Main Content */
.main-content {
    flex: 1;
    margin-left: 250px;
}

/* Top Navigation */
.top-nav {
    background-color: white;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.search-bar input {
    width: 300px;
    padding: 0.5rem 1rem;
    border: 1px solid #E5E7EB;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}

.user-actions {
    display: flex;
    align-items: center;
}

.notifications {
    position: relative;
    margin-right: 1.5rem;
    cursor: pointer;
}

.badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background-color: #EF4444;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 0.75rem;
    display: flex;
    justify-content: center;
    align-items: center;
}

.user-profile {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background-color: #E5E7EB;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: 0.75rem;
}

.user-info {
    font-size: 0.875rem;
}

.username {
    font-weight: 600;
}

.role {
    color: #6B7280;
    font-size: 0.75rem;
}

/* Dashboard Content */
.dashboard-content {
    padding: 2rem;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.page-header h1 {
    font-size: 1.875rem;
    font-weight: 700;
}

.date-range {
    color: #6B7280;
    font-size: 0.875rem;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background-color: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.stat-title {
    color: #6B7280;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-change {
    font-size: 0.75rem;
    font-weight: 600;
}

.positive {
    color: #10B981;
}

.negative {
    color: #EF4444;
}

/* Charts Section */
.charts-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.chart-container {
    background-color: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.chart-header h3 {
    font-size: 1rem;
    font-weight: 600;
}

.chart-placeholder {
    height: 250px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #F9FAFB;
    border-radius: 0.375rem;
    color: #6B7280;
}

select {
    padding: 0.375rem 0.75rem;
    border: 1px solid #E5E7EB;
    border-radius: 0.375rem;
    font-size: 0.875rem;
}

/* Recent Activity */
.recent-activity {
    background-color: white;
    border-radius: 0.5rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-header h3 {
    font-size: 1rem;
    font-weight: 600;
}

.section-header a {
    color: #3B82F6;
    font-size: 0.875rem;
    text-decoration: none;
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.activity-item {
    display: flex;
    align-items: center;
    padding-bottom: 1rem;
    border-bottom: 1px solid #E5E7EB;
}

.activity-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.activity-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-right: 1rem;
    font-size: 1rem;
}

.green {
    background-color: #D1FAE5;
    color: #10B981;
}

.blue {
    background-color: #DBEAFE;
    color: #3B82F6;
}

.yellow {
    background-color: #FEF3C7;
    color: #F59E0B;
}

.red {
    background-color: #FEE2E2;
    color: #EF4444;
}

.activity-desc {
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.activity-time {
    font-size: 0.75rem;
    color: #6B7280;
}

/* Responsive Styles */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .charts-section {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .sidebar {
        width: 64px;
    }
    
    .sidebar-nav li a span:not(.icon) {
        display: none;
    }
    
    .sidebar-header .logo {
        display: none;
    }
    
    .main-content {
        margin-left: 64px;
    }
    
    .search-bar input {
        width: 180px;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
CSS;
    }

    /**
     * Get Minimalist HTML
     */
    private function getMinimalistHTML(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimalist Template</title>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">Minimø</div>
                <ul class="nav-links">
                    <li><a href="#about">À propos</a></li>
                    <li><a href="#work">Travaux</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Simplicité et élégance.</h1>
            <p class="lead">Un design épuré pour un impact maximal.</p>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="section-header">
                <h2>À propos</h2>
                <div class="separator"></div>
            </div>
            <div class="about-content">
                <div class="about-text">
                    <p>Le minimalisme est plus qu'un style esthétique, c'est une philosophie qui valorise l'essentiel. Notre approche du design se concentre sur la simplicité, l'espace et la clarté.</p>
                    <p>Chaque élément a un but, rien n'est superflu. Cette discipline crée une expérience utilisateur élégante et intuitive.</p>
                </div>
                <div class="about-image">
                    <div class="image-placeholder">
                        <!-- Placeholder for an image -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Work Section -->
    <section id="work" class="work">
        <div class="container">
            <div class="section-header">
                <h2>Nos travaux</h2>
                <div class="separator"></div>
            </div>
            <div class="work-grid">
                <div class="work-item">
                    <div class="work-image">
                        <!-- Work image placeholder -->
                    </div>
                    <h3>Project One</h3>
                    <p>Design épuré pour une marque de mode contemporaine.</p>
                </div>
                <div class="work-item">
                    <div class="work-image">
                        <!-- Work image placeholder -->
                    </div>
                    <h3>Project Two</h3>
                    <p>Interface utilisateur minimaliste pour une application de productivité.</p>
                </div>
                <div class="work-item">
                    <div class="work-image">
                        <!-- Work image placeholder -->
                    </div>
                    <h3>Project Three</h3>
                    <p>Conception d'emballage simplifiée pour une gamme de produits premium.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quote Section -->
    <section class="quote">
        <div class="container">
            <blockquote>
                <p>"La perfection est atteinte, non pas quand il n'y a plus rien à ajouter, mais quand il n'y a plus rien à retirer."</p>
                <cite>— Antoine de Saint-Exupéry</cite>
            </blockquote>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="section-header">
                <h2>Contact</h2>
                <div class="separator"></div>
            </div>
            <div class="contact-content">
                <form class="contact-form">
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn">Envoyer</button>
                </form>
                <div class="contact-info">
                    <div class="info-item">
                        <h3>Email</h3>
                        <p>contact@minimo.com</p>
                    </div>
                    <div class="info-item">
                        <h3>Téléphone</h3>
                        <p>+33 1 23 45 67 89</p>
                    </div>
                    <div class="info-item">
                        <h3>Adresse</h3>
                        <p>123 Rue du Design<br>75001 Paris, France</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">Minimø</div>
                <div class="social-links">
                    <a href="#" class="social-link">Tw</a>
                    <a href="#" class="social-link">Ig</a>
                    <a href="#" class="social-link">Fb</a>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Minimø. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>
HTML;
    }

    /**
     * Get Minimalist CSS
     */
    private function getMinimalistCSS(): string
    {
        return <<<'CSS'
/* General styling */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Helvetica Neue', Arial, sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #fff;
}

.container {
    max-width: 1140px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

section {
    padding: 5rem 0;
}

h1, h2, h3 {
    font-weight: 300;
    line-height: 1.2;
}

h1 {
    font-size: 3.5rem;
    margin-bottom: 1rem;
}

h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

h3 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}

p {
    margin-bottom: 1.5rem;
}

a {
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
}

ul {
    list-style: none;
}

img {
    max-width: 100%;
    height: auto;
}

/* Header & Navigation */
header {
    padding: 2rem 0;
}

.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 1.8rem;
    font-weight: 300;
    letter-spacing: 2px;
}

.nav-links {
    display: flex;
    gap: 2rem;
}

.nav-links a {
    position: relative;
    padding-bottom: 0.25rem;
}

.nav-links a::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 1px;
    background-color: #333;
    transition: width 0.3s ease;
}

.nav-links a:hover::after {
    width: 100%;
}

/* Hero Section */
.hero {
    height: 80vh;
    display: flex;
    align-items: center;
    text-align: center;
    background-color: #f8f8f8;
}

.lead {
    font-size: 1.5rem;
    color: #666;
}

/* Section Headers */
.section-header {
    text-align: center;
    margin-bottom: 4rem;
}

.separator {
    width: 50px;
    height: 1px;
    background-color: #333;
    margin: 1.5rem auto;
}

/* About Section */
.about-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.image-placeholder {
    width: 100%;
    height: 400px;
    background-color: #f0f0f0;
}

/* Work Section */
.work {
    background-color: #f8f8f8;
}

.work-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(330px, 1fr));
    gap: 2rem;
}

.work-item {
    background-color: #fff;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
}

.work-image {
    width: 100%;
    height: 250px;
    background-color: #f0f0f0;
    margin-bottom: 1.5rem;
}

/* Quote Section */
.quote {
    padding: 8rem 0;
    background-color: #fff;
    text-align: center;
}

blockquote {
    max-width: 800px;
    margin: 0 auto;
}

blockquote p {
    font-size: 2rem;
    font-style: italic;
    line-height: 1.4;
    margin-bottom: 1.5rem;
}

blockquote cite {
    font-style: normal;
    font-size: 1rem;
    color: #666;
}

/* Contact Section */
.contact-content {
    display: grid;
    grid-template-columns: 3fr 2fr;
    gap: 4rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: #666;
}

input, textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    background-color: #f8f8f8;
    font-family: inherit;
    font-size: 1rem;
    transition: all 0.3s ease;
}

input:focus, textarea:focus {
    outline: none;
    border-color: #333;
    background-color: #fff;
}

.btn {
    display: inline-block;
    padding: 0.75rem 2rem;
    background-color: #333;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-family: inherit;
    font-size: 1rem;
}

.btn:hover {
    background-color: #555;
}

.contact-info {
    padding-top: 1rem;
}

.info-item {
    margin-bottom: 2rem;
}

.info-item h3 {
    margin-bottom: 0.5rem;
    color: #666;
}

/* Footer */
footer {
    background-color: #f8f8f8;
    padding: 4rem 0 2rem;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.social-links {
    display: flex;
    gap: 1.5rem;
}

.social-link {
    font-size: 1rem;
    font-weight: 500;
    padding: 0.5rem;
}

.social-link:hover {
    color: #666;
}

.copyright {
    text-align: center;
    color: #666;
    font-size: 0.9rem;
}

/* Responsive Styles */
@media (max-width: 992px) {
    .about-content,
    .contact-content {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
}

@media (max-width: 768px) {
    h1 {
        font-size: 2.5rem;
    }
    
    h2 {
        font-size: 2rem;
    }
    
    .navbar {
        flex-direction: column;
        gap: 1rem;
    }
    
    .hero {
        height: 60vh;
    }
    
    .work-grid {
        grid-template-columns: 1fr;
    }
}
CSS;
    }
}
