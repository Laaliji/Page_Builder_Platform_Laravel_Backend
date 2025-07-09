<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        
        foreach ($projects as $project) {
            // Create a home page for each project
            DB::table('pages')->insert([
                'id' => Str::uuid(),
                'title' => 'Home Page',
                'html_page_title' => $project->title . ' - Home',
                'html_content' => $this->getDefaultHTML($project->title),
                'css_content' => $this->getDefaultCSS(),
                'project_id' => $project->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create an about page
            DB::table('pages')->insert([
                'id' => Str::uuid(),
                'title' => 'About Us',
                'html_page_title' => $project->title . ' - About',
                'html_content' => $this->getAboutHTML($project->title),
                'css_content' => $this->getDefaultCSS(),
                'project_id' => $project->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Create a contact page
            DB::table('pages')->insert([
                'id' => Str::uuid(),
                'title' => 'Contact',
                'html_page_title' => $project->title . ' - Contact',
                'html_content' => $this->getContactHTML($project->title),
                'css_content' => $this->getDefaultCSS(),
                'project_id' => $project->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
    
    private function getDefaultHTML($projectTitle): string
    {
        return '<div class="container"><header><h1>Welcome to ' . $projectTitle . '</h1></header><main><p>This is your home page. Start building your amazing website with our powerful page builder!</p><div class="features"><h2>Features</h2><ul><li>Drag and drop editor</li><li>Responsive design</li><li>Custom templates</li></ul></div></main></div>';
    }
    
    private function getAboutHTML($projectTitle): string
    {
        return '<div class="container"><header><h1>About ' . $projectTitle . '</h1></header><main><p>Learn more about our project and what we do.</p><p>We are dedicated to providing the best page building experience with cutting-edge technology and user-friendly interfaces.</p></main></div>';
    }
    
    private function getContactHTML($projectTitle): string
    {
        return '<div class="container"><header><h1>Contact Us</h1></header><main><p>Get in touch with the ' . $projectTitle . ' team.</p><div class="contact-info"><p>Email: contact@example.com</p><p>Phone: +1 (555) 123-4567</p><p>Address: 123 Main St, City, State 12345</p></div></main></div>';
    }
    
    private function getDefaultCSS(): string
    {
        return '.container { max-width: 1200px; margin: 0 auto; padding: 20px; font-family: Arial, sans-serif; } header h1 { color: #333; margin-bottom: 20px; font-size: 2.5em; } main p { line-height: 1.6; color: #666; margin-bottom: 15px; } .features { margin-top: 30px; } .features h2 { color: #444; margin-bottom: 15px; } .features ul { list-style-type: disc; margin-left: 20px; } .features li { margin-bottom: 8px; color: #555; } .contact-info { background: #f5f5f5; padding: 20px; border-radius: 8px; margin-top: 20px; }';
    }
}
