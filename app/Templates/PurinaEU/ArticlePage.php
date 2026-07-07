<?php

namespace App\Templates\PurinaEU;

use App\Models\Article;
use CN\PHTML\ArrayTAG;
use CN\PHTML\Core\TAG;

class ArticlePage extends PurinaEUPage
{
    private Article $article;

    public function __construct(?string $slug = null)
    {
        $this->article = Article::query()->where('slug', $slug)->first()
            ?? Article::query()->orderByDesc('published_at')->firstOrFail();

        parent::__construct();
    }

    protected function pageTitle(): string
    {
        return $this->article->title;
    }

    protected function metaDescription(): string
    {
        return $this->article->excerpt;
    }

    protected function buildContent(): TAG|ArrayTAG|string
    {
        return new ArrayTAG([
            $this->buildTitleImage(),
            $this->buildBody(),
            $this->buildRelatedArticles(),
        ]);
    }

    private function buildTitleImage(): TAG
    {
        $hero = TAG::div('article-hero');
        $hero->append(TAG::img(null, null, null, src: $this->article->image, alt: $this->article->title)->allowContent(false));

        $caption = TAG::div('article-hero-caption');
        $caption->append(TAG::span('tag', $this->article->category));
        $caption->append(TAG::h1(null, $this->article->title));
        $hero->append($caption);

        return $hero;
    }

    private function buildBody(): TAG
    {
        $article = TAG::article('article-body');

        $paragraphs = preg_split('/\n\s*\n/', trim((string) $this->article->body)) ?: [];

        foreach ($paragraphs as $paragraph) {
            if ($paragraph === '') {
                continue;
            }
            $article->append(TAG::p(null, null, $paragraph));
        }

        return $article;
    }

    private function buildRelatedArticles(): TAG
    {
        $section = TAG::section(null, 'artigos-relacionados');

        $title = TAG::div('section-title');
        $title->append(TAG::h2(null, 'Continue lendo'));
        $section->append($title);

        $grid = TAG::div('grid grid-3');
        $related = Article::query()
            ->where('slug', '!=', $this->article->slug)
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        foreach ($related as $article) {
            $grid->append($this->articleCard($article));
        }
        $section->append($grid);

        return $section;
    }
}
