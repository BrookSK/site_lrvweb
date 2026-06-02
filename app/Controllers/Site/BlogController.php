<?php

declare(strict_types=1);

namespace App\Controllers\Site;

use Core\Controller;
use Core\Database;
use Core\Request;
use Core\Response;

class BlogController extends Controller
{
    public function index(Request $request, Response $response): string
    {
        $db = Database::getInstance();
        $page = max(1, (int) ($request->query('page') ?? 1));
        $perPage = 9;
        $offset = ($page - 1) * $perPage;

        $locale = \Core\I18n::getLocale();

        $posts = $db->fetchAll("
            SELECT p.*, bc.name as category_name, bc.slug as category_slug, u.name as author_name
            FROM blog_posts p
            LEFT JOIN blog_categories bc ON p.category_id = bc.id
            LEFT JOIN users u ON p.author_id = u.id
            WHERE p.status = 'published' AND p.deleted_at IS NULL AND p.language = :lang
            ORDER BY p.published_at DESC
            LIMIT {$perPage} OFFSET {$offset}
        ", ['lang' => $locale]);

        $total = $db->fetchOne("SELECT COUNT(*) as total FROM blog_posts WHERE status = 'published' AND deleted_at IS NULL AND language = :lang", ['lang' => $locale]);
        $totalPages = (int) ceil(($total['total'] ?? 0) / $perPage);

        $categories = $db->fetchAll("SELECT * FROM blog_categories ORDER BY name");

        return $this->view('site/blog', [
            'title' => 'Blog - LRV Web',
            'meta_description' => 'Artigos sobre desenvolvimento web, hospedagem, marketing digital e tecnologia.',
            'posts' => $posts,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ], 'site');
    }

    public function show(Request $request, Response $response, array $params): string
    {
        $slug = $params['slug'] ?? '';
        $db = Database::getInstance();

        $post = $db->fetchOne("
            SELECT p.*, bc.name as category_name, u.name as author_name
            FROM blog_posts p
            LEFT JOIN blog_categories bc ON p.category_id = bc.id
            LEFT JOIN users u ON p.author_id = u.id
            WHERE p.slug = :slug AND p.status = 'published' AND p.deleted_at IS NULL
        ", ['slug' => $slug]);

        if (!$post) {
            $this->response->setStatusCode(404);
            return \Core\View::render('errors/404');
        }

        // Incrementa views
        $db->query("UPDATE blog_posts SET views = views + 1 WHERE id = :id", ['id' => $post['id']]);

        // Posts relacionados
        $related = $db->fetchAll("
            SELECT title, slug, image, published_at FROM blog_posts 
            WHERE category_id = :cat_id AND id != :id AND status = 'published'
            ORDER BY published_at DESC LIMIT 3
        ", ['cat_id' => $post['category_id'], 'id' => $post['id']]);

        return $this->view('site/blog-post', [
            'title' => $post['meta_title'] ?: $post['title'],
            'meta_description' => $post['meta_description'] ?? $post['excerpt'] ?? '',
            'post' => $post,
            'related' => $related,
        ], 'site');
    }

    public function category(Request $request, Response $response, array $params): string
    {
        $slug = $params['slug'] ?? '';
        $db = Database::getInstance();

        $category = $db->fetchOne("SELECT * FROM blog_categories WHERE slug = :slug", ['slug' => $slug]);

        if (!$category) {
            $this->response->setStatusCode(404);
            return \Core\View::render('errors/404');
        }

        $posts = $db->fetchAll("
            SELECT p.*, u.name as author_name
            FROM blog_posts p
            LEFT JOIN users u ON p.author_id = u.id
            WHERE p.category_id = :cat_id AND p.status = 'published' AND p.deleted_at IS NULL
            ORDER BY p.published_at DESC
        ", ['cat_id' => $category['id']]);

        return $this->view('site/blog', [
            'title' => $category['name'] . ' - Blog LRV Web',
            'posts' => $posts,
            'categories' => $db->fetchAll("SELECT * FROM blog_categories ORDER BY name"),
            'currentPage' => 1,
            'totalPages' => 1,
        ], 'site');
    }

    public function tag(Request $request, Response $response, array $params): string
    {
        $tag = $params['slug'] ?? '';
        $db = Database::getInstance();

        $posts = $db->fetchAll("
            SELECT p.*, bc.name as category_name, u.name as author_name
            FROM blog_posts p
            LEFT JOIN blog_categories bc ON p.category_id = bc.id
            LEFT JOIN users u ON p.author_id = u.id
            WHERE p.tags LIKE :tag AND p.status = 'published' AND p.deleted_at IS NULL
            ORDER BY p.published_at DESC
        ", ['tag' => "%{$tag}%"]);

        return $this->view('site/blog', [
            'title' => "Tag: {$tag} - Blog LRV Web",
            'posts' => $posts,
            'categories' => $db->fetchAll("SELECT * FROM blog_categories ORDER BY name"),
            'currentPage' => 1,
            'totalPages' => 1,
        ], 'site');
    }
}
