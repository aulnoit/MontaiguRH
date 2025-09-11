<?php
// URL du flux RSS Medium
$rss_url = "https://medium.com/feed/@aulnoit1";

// Charger le flux RSS
$rss = @simplexml_load_file($rss_url);

if ($rss && isset($rss->channel->item)) {
    $articles = array_slice($rss->channel->item, 0, 3); // Limite à 3 articles

    foreach ($articles as $item) {
        $title = (string) $item->title;
        $link = (string) $item->link;
        $pubDate = date("d F Y", strtotime($item->pubDate));
        $isoDate = date("Y-m-d", strtotime($item->pubDate));
        $description = strip_tags((string) $item->description);
        $excerpt = mb_substr($description, 0, 150) . "...";

        // Extraire l'image du contenu
        preg_match('/<img[^>]+src="([^">]+)"/', $item->description, $imgMatch);
        $image = $imgMatch[1] ?? 'https://via.placeholder.com/600x400?text=Article+Medium';

        // Générer le HTML de l'article
        echo <<<HTML
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="blog-card">
                <img src="$image" alt="$div class="blog-card-content">
                    <div class="blog-card-date">$pubDate</div>
                    <h3 class="blog-card-title">$title</h3>
                    <p class="blog-card-excerpt">$excerpt</p>
                    <a href="$link"ite <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Balise JSON-LD pour le SEO -->
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "BlogPosting",
          "headline": "$title",
          "image": "$image",
          "author": {
            "@type": "Person",
            "name": "Gérald HOUZE"
          },
          "publisher": {
            "@type": "Organization",
            "name": "Groupe DESERT",
            "logo": {
              "@type": "ImageObject",
              "url": "https://tonsite.com/logo.png"
            }
          },
          "datePublished": "$isoDate",
          "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "$link"
          }
        }
        </script>
HTML;
    }
} else {
    echo "<p>Impossible de charger les articles Medium pour le moment.</p>";
}
?>
