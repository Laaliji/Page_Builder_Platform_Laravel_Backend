<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplateSeeder extends Seeder
{
    public function run()
    {
        // Minimalistic Template
        Template::create([
            'name' => 'Modèle minimaliste',
            'html_content' => '
                <!DOCTYPE html>
                <html lang="en">
                    <head>
                        <meta charset="UTF-8" />
                        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                        <title>Minimalistic Template</title>
                    </head>
                    <body>
                        <div class="container">
                        <h1>Welcome to Minimalistic</h1>
                        <div class="content-wrapper">
                            <!-- Image Section -->
                            <div class="image-wrapper">
                            <img src="image.jpg" alt="About Us Image" class="image" />
                            </div>
                            <!-- Features Section -->
                            <div class="features-wrapper">
                            <h2>About Us</h2>
                            <p>
                                This is a minimalistic template with basic components to get started
                                quickly.
                            </p>
                            <h2>Features</h2>
                            <ul>
                                <li>Simple design</li>
                                <li>Clean layout</li>
                                <li>Optimized for clarity</li>
                            </ul>
                            <a href="#" class="button">Learn More</a>
                            </div>
                        </div>
                        </div>
                    </body>
                </html>
            ',
            'css_content' => '
                    body {
                        font-family: Arial, sans-serif;
                        margin: 0;
                        padding: 0;
                        background-color: #f4f4f4;
                        color: #333;
                    }
                    .container {
                        max-width: 100%;
                        margin: 20px auto;
                        padding: 20px;
                        background-color: #fff;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                    }
                    h1 {
                        text-align: center;
                    }
                    .content-wrapper {
                        display: flex;
                        flex-wrap: wrap;
                        align-items: center;
                        gap: 20px;
                        margin: 20px 0;
                    }
                    .image-wrapper {
                        flex: 1;
                        text-align: center;
                    }
                    .image {
                        max-width: 100%;
                        height: auto;
                        border-radius: 8px;
                    }
                    .features-wrapper {
                        flex: 1;
                    }
                    .features-wrapper h2 {
                        margin-bottom: 10px;
                    }
                    .features-wrapper ul {
                        list-style-type: disc;
                        padding-left: 20px;
                    }
                    .button {
                        display: inline-block;
                        padding: 10px 20px;
                        margin-top: 10px;
                        color: #fff;
                        background-color: #007bff;
                        text-align: center;
                        text-decoration: none;
                        border-radius: 5px;
                    }
            ',
            'description' => 'Conception élégante et simple pour un look moderne.',
        ]);

        // Dashboard Template
        Template::create([
            'name' => 'Modèle de tableau de bord',
            'html_content' => '
                <!DOCTYPE html>
                <html lang="en">
                  <head>
                    <meta charset="UTF-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <title>Dashboard Template</title>
                  </head>
                  <body>
                    <div class="header">
                      <h1>Dashboard</h1>
                    </div>
                    <div class="main">
                      <div class="sidebar">
                        <h2>Navigation</h2>
                        <ul>
                          <li><a href="#">Home</a></li>
                          <li><a href="#">Settings</a></li>
                          <li><a href="#">Profile</a></li>
                        </ul>
                      </div>
                      <div class="content">
                        <div class="widget">
                          <h2>Statistics</h2>
                          <img src="image.jpg" alt="Statistics Graph" class="image" />
                          <div class="progress-bar">
                            <div class="progress"></div>
                          </div>
                          <p>60% Complete</p>
                        </div>
                        <div class="widget">
                          <h2>Notifications</h2>
                          <p>No new notifications.</p>
                        </div>
                        <div class="widget">
                          <h2>Recent Activity</h2>
                          <ul>
                            <li>User A updated profile</li>
                            <li>User B posted a comment</li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </body>
                </html>
            ',
            'css_content' => '
                body {
                  font-family: Arial, sans-serif;
                  margin: 0;
                  padding: 0;
                  background-color: #f0f2f5;
                }
                .header {
                  background-color: #3b5998;
                  color: #fff;
                  padding: 20px;
                  text-align: center;
                }
                .main {
                  display: flex;
                  gap: 20px;
                  padding: 20px;
                }
                .sidebar {
                  width: 25%;
                  background-color: #fff;
                  padding: 20px;
                  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                  border-radius: 8px;
                }
                .content {
                  width: 75%;
                  background-color: #fff;
                  padding: 20px;
                  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                  border-radius: 8px;
                }
                .widget {
                  margin-bottom: 20px;
                }
                .progress-bar {
                  height: 10px;
                  width: 100%;
                  background-color: #e0e0e0;
                  border-radius: 5px;
                  overflow: hidden;
                }
                .progress {
                  height: 10px;
                  background-color: #4caf50;
                  width: 60%;
                }
                .image {
                  display: block;
                  margin: 10px 0;
                  max-width: 100%;
                  border-radius: 8px;
                }
            ',
            'description' => 'mise en page prédéfinie pour la visualisation.',
        ]);
    }
}
