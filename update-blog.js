
const https = require('https');
const fs = require('fs');

const rssUrl = 'https://api.rss2json.com/v1/api.json?rss_url=https://medium.com/feed/@aulnoit1';

https.get(rssUrl, (res) => {
    let data = '';
    res.on('data', chunk => data += chunk);
    res.on('end', () => {
        const json = JSON.parse(data);
        const articles = json.items.slice(0, 3);

        let html = `<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog RH - Articles Medium</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<section id="blog" class="py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center">Actualités & Conseils RH</h2>
        <p class="text-center mb-5">Découvrez nos derniers articles sur la gestion des ressources humaines</p>
        <div class="row">`;

        articles.forEach(item => {
            html += `
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="blog-card">
                    <img src="${item.thumbnail}" alt="${item.title}" class="blog-card-img">
                    <div class="blog-card-content">
                        <div class="blog-card-date">${new Date(item.pubDate).toLocaleDateString('fr-FR')}</div>
                        <h3 class="blog-card-title">${item.title}</h3>
                        <p class="blog-card-excerpt">${item.description.slice(0, 150)}...</p>
                        <a href="${item.link}" target="_blank" class="blog-card-link">Lire la suite <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>`;
        });

        html += `</div>
        <div class="text-center mt-4">
            <a href="https://medium.com/@aulnoit1" target="_blank" class="btn btn-primary">Voir tous les articles</a>
        </div>
    </div>
</section>
</body>
</html>`;

        fs.writeFileSync('blog.html', html);
        console.log('Fichier blog.html généré avec succès.');
    });
}).on('error', err => {
    console.error('Erreur lors de la récupération du flux RSS :', err);
});
