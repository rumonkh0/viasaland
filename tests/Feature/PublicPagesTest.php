<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    /**
     * Test home page loads successfully.
     */
    public function test_home_page_loads_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Adiba Global');
        $response->assertSee('Blog');
    }

    /**
     * Test index.html redirects to home.
     */
    public function test_index_html_redirects_to_home(): void
    {
        $response = $this->get('/index.html');
        $response->assertRedirect('/');
    }

    /**
     * Test blog page loads and contains articles.
     */
    public function test_blog_page_loads_successfully(): void
    {
        $response = $this->get('/blog');
        $response->assertStatus(200);
        $response->assertSee('Adiba Global Visa Blog');
        $response->assertSee('Navigating UK Student Visa Rules');
        $response->assertSee('Cracking the US F-1 Visa Interview');

        $htmlResponse = $this->get('/blog.html');
        $htmlResponse->assertStatus(200);
        $htmlResponse->assertSee('Adiba Global Visa Blog');
    }

    /**
     * Test all public content pages load with both clean and .html routes.
     */
    public function test_all_public_content_pages_load(): void
    {
        $pages = [
            'services',
            'about',
            'countries',
            'contact',
            'student-visa',
            'work-visa',
            'tourist-visa',
            'business-visa',
            'umrah-visa',
            'faq',
            'documents',
            'terms',
            'privacy',
        ];

        foreach ($pages as $page) {
            $resClean = $this->get("/{$page}");
            $resClean->assertStatus(200);

            $resHtml = $this->get("/{$page}.html");
            $resHtml->assertStatus(200);
        }
    }

    /**
     * Test public navbar does not show admin links.
     */
    public function test_public_navbar_does_not_show_admin_links(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('/admin');
        $response->assertDontSee('Admin Portal');
    }
}
