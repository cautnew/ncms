<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\FaqCategory;
use App\Models\PageContent;
use App\Models\Product;
use Illuminate\Database\Seeder;

class PurinaEuContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedArticles();
        $this->seedProducts();
        $this->seedFaq();
        $this->seedPageContents();
    }

    private function seedArticles(): void
    {
        $articles = [
            [
                'slug' => 'nutricao-balanceada-caes',
                'title' => 'Nutrição balanceada para cães em todas as fases da vida',
                'excerpt' => 'Entenda como os nutrientes certos mudam conforme seu cão cresce, do filhote ao sênior.',
                'category' => 'Nutrição',
                'author' => 'Equipe Purina',
                'published_at' => '2026-06-12',
                'views' => '18,4k',
                'image' => 'https://picsum.photos/seed/purina-article-1/480/320',
            ],
            [
                'slug' => 'sinais-alergia-alimentar-gatos',
                'title' => '7 sinais de alergia alimentar em gatos que você não pode ignorar',
                'excerpt' => 'Coceira, queda de pelo e problemas digestivos podem estar ligados à alimentação do seu gato.',
                'category' => 'Cuidados',
                'author' => 'Dra. Marina Alves',
                'published_at' => '2026-06-02',
                'views' => '15,1k',
                'image' => 'https://picsum.photos/seed/purina-article-2/480/320',
            ],
            [
                'slug' => 'guia-transicao-alimentar',
                'title' => 'Guia completo de transição alimentar sem estresse',
                'excerpt' => 'Um passo a passo para trocar a ração do seu pet sem causar desconforto digestivo.',
                'category' => 'Nutrição',
                'author' => 'Equipe Purina',
                'published_at' => '2026-05-28',
                'views' => '12,7k',
                'image' => 'https://picsum.photos/seed/purina-article-3/480/320',
            ],
            [
                'slug' => 'exercicios-para-filhotes',
                'title' => 'Exercícios recomendados para filhotes de acordo com a raça',
                'excerpt' => 'Cada porte e raça exige um tipo de estímulo físico diferente. Veja o que a ciência recomenda.',
                'category' => 'Comportamento',
                'author' => 'Rafael Souza',
                'published_at' => '2026-05-19',
                'views' => '9,8k',
                'image' => 'https://picsum.photos/seed/purina-article-4/480/320',
            ],
            [
                'slug' => 'sustentabilidade-embalagens',
                'title' => 'Como estamos reduzindo o impacto ambiental das nossas embalagens',
                'excerpt' => 'Conheça os investimentos da marca em materiais recicláveis até 2030.',
                'category' => 'Novidades',
                'author' => 'Equipe Purina',
                'published_at' => '2026-05-05',
                'views' => '7,2k',
                'image' => 'https://picsum.photos/seed/purina-article-5/480/320',
            ],
            [
                'slug' => 'obesidade-pet-prevencao',
                'title' => 'Obesidade em pets: como prevenir e identificar os primeiros sinais',
                'excerpt' => 'Veterinários explicam os riscos do sobrepeso e como ajustar a rotina alimentar.',
                'category' => 'Cuidados',
                'author' => 'Dra. Marina Alves',
                'published_at' => '2026-04-22',
                'views' => '6,5k',
                'image' => 'https://picsum.photos/seed/purina-article-6/480/320',
            ],
        ];

        foreach ($articles as $article) {
            Article::query()->updateOrCreate(
                ['slug' => $article['slug']],
                $article + ['body' => $this->defaultArticleBody($article['excerpt'])]
            );
        }
    }

    private function defaultArticleBody(string $excerpt): string
    {
        return implode("\n\n", [
            $excerpt,
            'Neste artigo, reunimos as principais recomendações de veterinários e nutricionistas para ajudar você a tomar as melhores decisões para o seu pet.',
            'Entre os principais ganhos observados estão o aumento de energia, melhora do pelo e da pele, e fortalecimento do sistema imunológico.',
            'Ao aplicar essas orientações, observe sempre o comportamento do seu pet e consulte um veterinário em caso de qualquer alteração incomum.',
            'Pequenos ajustes na rotina alimentar podem gerar grandes resultados na qualidade de vida do seu pet.',
        ]);
    }

    private function seedProducts(): void
    {
        $usageText = 'Ofereça a quantidade recomendada na embalagem, dividida em 2 refeições diárias, e mantenha água fresca sempre disponível.';
        $ingredientsText = 'Frango, arroz, gordura animal, polpa de beterraba, óleo de peixe, vitaminas e minerais quelatados.';

        $products = [
            [
                'slug' => 'pro-plan-adulto-frango',
                'name' => 'Pro Plan Adulto Frango & Arroz',
                'category' => 'Cães',
                'price' => '€ 42,90',
                'old_price' => '€ 49,90',
                'rating' => '4,8',
                'reviews' => 312,
                'image' => 'https://picsum.photos/seed/purina-product-1/560/560',
                'description' => 'Ração completa para cães adultos de todas as raças, formulada com frango de alta qualidade e OPTIPRO® para fortalecer o sistema imunológico.',
                'specs' => ['Peso' => '15 kg', 'Sabor' => 'Frango & Arroz', 'Faixa etária' => 'Adulto', 'Tipo' => 'Ração seca'],
            ],
            [
                'slug' => 'felix-sensacoes-gatos',
                'name' => 'Felix Sensações Sachês',
                'category' => 'Gatos',
                'price' => '€ 12,50',
                'old_price' => null,
                'rating' => '4,6',
                'reviews' => 198,
                'image' => 'https://picsum.photos/seed/purina-product-2/560/560',
                'description' => 'Sachês em molho com pedaços macios de carne e peixe, pensados para gatos exigentes.',
                'specs' => ['Peso' => '12 x 85 g', 'Sabor' => 'Carne & Peixe', 'Faixa etária' => 'Adulto', 'Tipo' => 'Úmido'],
            ],
            [
                'slug' => 'purina-one-filhotes',
                'name' => 'Purina ONE Filhotes',
                'category' => 'Cães',
                'price' => '€ 29,90',
                'old_price' => null,
                'rating' => '4,9',
                'reviews' => 421,
                'image' => 'https://picsum.photos/seed/purina-product-3/560/560',
                'description' => 'Desenvolvida para apoiar o crescimento saudável de filhotes, com cálcio para ossos fortes.',
                'specs' => ['Peso' => '3 kg', 'Sabor' => 'Frango', 'Faixa etária' => 'Filhote', 'Tipo' => 'Ração seca'],
            ],
            [
                'slug' => 'proplan-veterinary-renal',
                'name' => 'Pro Plan Veterinary Renal',
                'category' => 'Cães',
                'price' => '€ 58,00',
                'old_price' => '€ 64,00',
                'rating' => '4,7',
                'reviews' => 87,
                'image' => 'https://picsum.photos/seed/purina-product-4/560/560',
                'description' => 'Dieta especial indicada por veterinários para apoio à função renal.',
                'specs' => ['Peso' => '10 kg', 'Sabor' => 'Vitela', 'Faixa etária' => 'Adulto/Sênior', 'Tipo' => 'Ração seca'],
            ],
            [
                'slug' => 'gourmet-gold-gatos',
                'name' => 'Gourmet Gold Patê',
                'category' => 'Gatos',
                'price' => '€ 9,90',
                'old_price' => null,
                'rating' => '4,5',
                'reviews' => 154,
                'image' => 'https://picsum.photos/seed/purina-product-5/560/560',
                'description' => 'Patê cremoso rico em proteínas para gatos adultos de paladar refinado.',
                'specs' => ['Peso' => '8 x 85 g', 'Sabor' => 'Frango & Fígado', 'Faixa etária' => 'Adulto', 'Tipo' => 'Úmido'],
            ],
            [
                'slug' => 'pro-plan-senior-7',
                'name' => 'Pro Plan Sênior 7+',
                'category' => 'Cães',
                'price' => '€ 45,50',
                'old_price' => null,
                'rating' => '4,8',
                'reviews' => 132,
                'image' => 'https://picsum.photos/seed/purina-product-6/560/560',
                'description' => 'Fórmula para cães sêniores com antioxidantes que ajudam a manter a vitalidade.',
                'specs' => ['Peso' => '12 kg', 'Sabor' => 'Frango & Arroz', 'Faixa etária' => 'Sênior', 'Tipo' => 'Ração seca'],
            ],
        ];

        foreach ($products as $product) {
            Product::query()->updateOrCreate(
                ['slug' => $product['slug']],
                $product + ['usage_text' => $usageText, 'ingredients_text' => $ingredientsText]
            );
        }
    }

    private function seedFaq(): void
    {
        $categories = [
            'Produtos' => [
                ['question' => 'Como escolher a ração ideal para o meu pet?', 'answer' => 'Considere idade, porte, nível de atividade e eventuais restrições de saúde. Nossos especialistas recomendam consultar um veterinário antes de trocar a alimentação.'],
                ['question' => 'As embalagens da Purina são recicláveis?', 'answer' => 'Sim, estamos migrando toda a nossa linha para embalagens recicláveis ou reutilizáveis até 2030.'],
                ['question' => 'Onde encontro o lote e a validade do produto?', 'answer' => 'A informação está impressa na parte inferior da embalagem, junto ao código de barras.'],
            ],
            'Pedidos e Entregas' => [
                ['question' => 'Qual o prazo de entrega dos pedidos online?', 'answer' => 'O prazo médio é de 2 a 5 dias úteis, variando conforme a região de entrega.'],
                ['question' => 'Posso alterar o endereço depois de finalizar a compra?', 'answer' => 'Sim, desde que o pedido ainda não tenha sido despachado. Entre em contato com o suporte o quanto antes.'],
                ['question' => 'Como funciona a política de devolução?', 'answer' => 'Você tem até 14 dias corridos após o recebimento para solicitar a devolução, desde que o produto esteja lacrado.'],
            ],
            'Nutrição' => [
                ['question' => 'Com que frequência devo alimentar meu pet?', 'answer' => 'Cães e gatos adultos geralmente devem ser alimentados 2 vezes ao dia, mas filhotes podem precisar de 3 a 4 refeições.'],
                ['question' => 'Posso misturar ração seca e úmida?', 'answer' => 'Sim, essa combinação é segura e pode aumentar a palatabilidade, desde que respeitada a quantidade calórica diária.'],
            ],
        ];

        $categoryOrder = 0;
        foreach ($categories as $name => $items) {
            $category = FaqCategory::query()->updateOrCreate(['name' => $name], ['order' => $categoryOrder++]);

            $itemOrder = 0;
            foreach ($items as $item) {
                $category->items()->updateOrCreate(
                    ['question' => $item['question']],
                    ['answer' => $item['answer'], 'order' => $itemOrder++]
                );
            }
        }
    }

    private function seedPageContents(): void
    {
        $pages = [
            'home' => [
                'hero' => [
                    'title' => 'Nutrição que seu pet merece, todos os dias',
                    'subtitle' => 'Rações e cuidados desenvolvidos com ciência para cada fase da vida de cães e gatos.',
                    'button_label' => 'Ver produtos',
                    'button_href' => '/purinaeu/produto',
                ],
                'info_blocks' => [
                    ['icon' => '🥩', 'title' => 'Nutrição Balanceada', 'text' => 'Fórmulas desenvolvidas com base em décadas de pesquisa nutricional para pets.'],
                    ['icon' => '🩺', 'title' => 'Aprovado por Veterinários', 'text' => 'Produtos testados e recomendados por especialistas em saúde animal.'],
                    ['icon' => '🌱', 'title' => 'Sustentabilidade', 'text' => 'Ingredientes de origem responsável e embalagens com menor impacto ambiental.'],
                    ['icon' => '💬', 'title' => 'Suporte 24/7', 'text' => 'Equipe de atendimento pronta para tirar dúvidas sobre nutrição e produtos.'],
                ],
                'cta' => [
                    'title' => 'Conheça a história por trás da Purina',
                    'text' => 'Décadas de ciência nutricional dedicadas ao bem-estar de cães e gatos.',
                    'button_label' => 'Conheça nossa marca',
                    'button_href' => '/purinaeu/marca',
                ],
            ],
            'brand' => [
                'hero' => [
                    'title' => 'Ciência e amor por trás de cada tigela',
                    'subtitle' => 'Há mais de 90 anos ajudando pets e seus tutores a viverem vidas melhores, mais longas e mais felizes juntos.',
                    'button_label' => 'Conheça os produtos',
                    'button_href' => '/purinaeu/produto',
                ],
                'story_history' => [
                    'title' => 'Nossa história',
                    'text1' => 'Fundada com o propósito de melhorar a vida de cães e gatos através da nutrição, a Purina reúne veterinários, nutricionistas e cientistas dedicados a cada fórmula.',
                    'text2' => 'Cada produto passa por rigorosos testes de qualidade antes de chegar até você e seu pet.',
                    'image' => 'https://picsum.photos/seed/purina-brand-1/560/420',
                ],
                'story_sustainability' => [
                    'title' => 'Sustentabilidade',
                    'text' => 'Trabalhamos para reduzir o impacto ambiental em toda a cadeia produtiva, de embalagens recicláveis a parcerias com fornecedores responsáveis.',
                    'image' => 'https://picsum.photos/seed/purina-brand-2/560/420',
                ],
                'stats' => [
                    ['number' => '90+', 'label' => 'anos de história'],
                    ['number' => '60+', 'label' => 'países atendidos'],
                    ['number' => '1M+', 'label' => 'pets mais saudáveis'],
                ],
                'values' => [
                    ['icon' => '🔬', 'title' => 'Ciência', 'text' => 'Pesquisa contínua para desenvolver fórmulas cada vez mais eficazes.'],
                    ['icon' => '🏅', 'title' => 'Qualidade', 'text' => 'Controle rigoroso em todas as etapas da produção.'],
                    ['icon' => '🌍', 'title' => 'Sustentabilidade', 'text' => 'Compromisso com um futuro mais responsável para o planeta.'],
                    ['icon' => '🤝', 'title' => 'Comunidade', 'text' => 'Parcerias com veterinários e ONGs de proteção animal.'],
                ],
                'testimonial' => [
                    'quote' => 'Desde que troquei a alimentação do meu cão, notei mais energia e um pelo muito mais saudável.',
                    'author' => 'Beatriz Nogueira, tutora de Golden Retriever',
                ],
                'cta' => [
                    'title' => 'Faça parte dessa jornada',
                    'text' => 'Descubra o produto ideal para o seu pet e experimente a diferença da nutrição científica.',
                    'button_label' => 'Ver produtos',
                    'button_href' => '/purinaeu/produto',
                ],
            ],
            'faq' => [
                'intro' => [
                    'title' => 'Perguntas Frequentes',
                    'text' => 'Reunimos as dúvidas mais comuns dos nossos clientes. Não encontrou o que procurava? Fale com a nossa equipe.',
                ],
                'cta' => [
                    'title' => 'Não encontrou sua resposta?',
                    'text' => 'Nossa equipe de suporte está pronta para ajudar você e o seu pet.',
                    'button_label' => 'Fale conosco',
                    'button_href' => '#',
                ],
            ],
            'articles' => [
                'intro' => [
                    'title' => 'Todos os artigos',
                    'text' => 'Dicas e orientações elaboradas por veterinários e especialistas em nutrição animal.',
                ],
            ],
        ];

        foreach ($pages as $page => $data) {
            PageContent::query()->updateOrCreate(['page' => $page], ['data' => $data]);
        }
    }
}
