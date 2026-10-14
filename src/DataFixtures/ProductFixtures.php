<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function __construct(
        private \Symfony\Component\String\Slugger\SluggerInterface $slugger,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // ════════════════════════════════════════════════
        //  1. LES CATÉGORIES
        // ════════════════════════════════════════════════
        $categoriesData = [
            'Mobilier' => 'Meubles anciens et pièces de menuiserie d\'exception.',
            'Horlogerie' => 'Pendules, cartels et montres de collection.',
            'Objets d\'art' => 'Sculptures, miroirs et objets décoratifs.',
            'Luminaires' => 'Lustres, appliques et lampes d\'époque.',
            'Céramiques' => 'Faïences, porcelaines et grès anciens.',
            'Textiles & Tapisseries' => 'Tapisseries, broderies et linge de maison ancien.',
        ];

        $categories = [];

        foreach ($categoriesData as $name => $description) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription($description);
            $category->setSlug(strtolower($this->slugger->slug($name)->toString()));

            $manager->persist($category);
            $categories[$name] = $category;
        }

        // ════════════════════════════════════════════════
        //  2. LES 30 PRODUITS
        // ════════════════════════════════════════════════
        //  [nom, slug, catégorie, description, matériau, style,
        //   origine, couleur, dimensions, poids, prix, stock]
        $productsData = [
            [
                'Commode sauteuse en acajou',
                'commode-sauteuse-en-acajou',
                'Mobilier',
                'Commode galbée à trois tiroirs, marqueterie de bois de rose, pieds cambrés terminés en sabots. Restauration d\'usage, serrures d\'origine.',
                'Acajou et bois de rose',
                'Louis XV',
                'France',
                'Brun foncé',
                '120 × 58 × 85 cm',
                '34 kg',
                '1250.00',
                1,
            ],
            [
                'Fauteuil Voltaire en noyer',
                'fauteuil-voltaire-en-noyer',
                'Mobilier',
                'Fauteuil à dossier cabriolet, assise et dossier tapissés de velours côtelé vert bouteille. Sculptures florales sur les accotoirs.',
                'Noyer massif',
                'Napoléon III',
                'France',
                'Beige et vert',
                '70 × 65 × 95 cm',
                '12 kg',
                '480.00',
                2,
            ],
            [
                'Table de ferme en chêne massif',
                'table-de-ferme-en-chene-massif',
                'Mobilier',
                'Table de ferme à six pieds, plateau monoxyle, traces de vieillissement assumées. Structure parfaitement stable.',
                'Chêne massif',
                'XVIIIe siècle',
                'France',
                'Miel',
                '220 × 90 × 76 cm',
                '58 kg',
                '690.00',
                1,
            ],
            [
                'Bureau secrétaire en merisier',
                'bureau-secretaire-en-merisier',
                'Mobilier',
                'Secrétaire à abattant, intérieur garni de casiers et d\'un tiroir secret. Marquetterie en frisage. Clé d\'origine.',
                'Merisier',
                'Louis-Philippe',
                'France',
                'Roux',
                '95 × 45 × 140 cm',
                '42 kg',
                '780.00',
                1,
            ],
            [
                'Buffet deux corps en noyer',
                'buffet-deux-corps-en-noyer',
                'Mobilier',
                'Buffet à deux corps, quatre portes et trois tiroirs. Décor de fleurs sculptées sur les traverses. Intérieur en chêne d\'origine.',
                'Noyer',
                'XIXe siècle',
                'France',
                'Noir et miel',
                '150 × 55 × 210 cm',
                '76 kg',
                '980.00',
                1,
            ],
            [
                'Guéridon en marqueterie',
                'gueridon-en-marqueterie',
                'Mobilier',
                'Guéridon à piètement tripode, plateau circulaire à marqueterie de fleurs. Pieds fuselés en acajou.',
                'Bois de rose et acajou',
                'Napoléon III',
                'France',
                'Brun roux',
                '⌀ 62 × 72 cm',
                '9 kg',
                '340.00',
                1,
            ],
            [
                'Armoire normande en chêne',
                'armoire-normande-en-chene',
                'Mobilier',
                'Armoire à deux portes, panneaux moulurés, corniche chantournée. Ferrures en fer forgé d\'origine, clé fonctionnelle.',
                'Chêne',
                'XIXe siècle',
                'France (Normandie)',
                'Chêne clair',
                '130 × 55 × 205 cm',
                '68 kg',
                '1150.00',
                1,
            ],
            [
                'Pendule de cheminée en marbre',
                'pendule-de-cheminee-en-marbre',
                'Horlogerie',
                'Pendule à colonnes en marbre noir et bronze doré. Mouvement à ressort révisé, sonnerie des heures et demi-heures.',
                'Marbre et bronze',
                'Napoléon III',
                'France',
                'Noir et or',
                '34 × 18 × 42 cm',
                '6 kg',
                '890.00',
                1,
            ],
            [
                'Horloge comtoise à balancier',
                'horloge-comtoise-a-balancier',
                'Horlogerie',
                'Comtoise à cadran émaillé, fronton en bois peint, poids et balancier d\'origine. Sonnerie de l\'heure et de la demie.',
                'Chêne et laiton',
                'XIXe siècle',
                'France (Franche-Comté)',
                'Bordeaux et laiton',
                '38 × 20 × 250 cm',
                '15 kg',
                '520.00',
                1,
            ],
            [
                'Cartel d\'applique Louis XVI',
                'cartel-d-applique-louis-xvi',
                'Horlogerie',
                'Cartel d\'applique en bronze doré, décor de guirlandes et de rubans. Cadran signé du maître horloger. Mouvement révisé.',
                'Bronze doré',
                'Louis XVI',
                'France',
                'Doré',
                '26 × 12 × 45 cm',
                '4 kg',
                '1340.00',
                1,
            ],
            [
                'Pendulette de voyage Art Déco',
                'pendulette-de-voyage-art-deco',
                'Horlogerie',
                'Pendulette de voyage dans son écrin d\'origine, laiton et verre biseauté. Mécanisme remonté et testé, très bonne précision.',
                'Laiton et verre',
                'Art Déco',
                'France',
                'Doré et noir',
                '12 × 8 × 14 cm',
                '1 kg',
                '280.00',
                2,
            ],
            [
                'Réveil de chevet mécanique',
                'reveil-de-chevet-mecanique',
                'Horlogerie',
                'Réveil de chevet à double barillet, boîtier chromé, cadran crème. Fonctionne parfaitement, sonnerie franche.',
                'Métal chromé et verre',
                'Art Déco',
                'Suisse',
                'Chrome et crème',
                '10 × 6 × 13 cm',
                '0.6 kg',
                '95.00',
                4,
            ],
            [
                'Miroir soleil en laiton doré',
                'miroir-soleil-en-laiton-dore',
                'Objets d\'art',
                'Miroir circulaire à rayons alternés, laiton doré d\'origine. Glace au mercure légèrement piquetée, patine authentique.',
                'Laiton doré',
                'Art Déco',
                'France',
                'Doré',
                '⌀ 52 cm',
                '3 kg',
                '320.00',
                1,
            ],
            [
                'Coffre de voyage en cuir clouté',
                'coffre-de-voyage-en-cuir-cloute',
                'Objets d\'art',
                'Malle de voyage en cuir fauve, cloutée laiton, serrure fonctionnelle. Intérieur doublé de toile de lin d\'origine.',
                'Cuir, bois et laiton',
                'Fin XIXe siècle',
                'Royaume-Uni',
                'Fauve',
                '80 × 45 × 35 cm',
                '14 kg',
                '640.00',
                1,
            ],
            [
                'Statue en bronze patiné',
                'statue-en-bronze-patine',
                'Objets d\'art',
                'Sculpture en bronze à patine médaille représentant un faune jouant de la flûte. Signée sur la terrasse, socle en marbre.',
                'Bronze et marbre',
                'XIXe siècle',
                'France',
                'Vert-de-gris',
                '22 × 18 × 48 cm',
                '7 kg',
                '780.00',
                1,
            ],
            [
                'Boîte à bijoux en marqueterie',
                'boite-a-bijoux-en-marqueterie',
                'Objets d\'art',
                'Boîte rectangulaire à couvercle bombé, marqueterie de bois précieux en losanges. Intérieur doublé de velours rouge.',
                'Bois précieux et laiton',
                'Napoléon III',
                'France',
                'Brun et rouge',
                '24 × 16 × 11 cm',
                '1.2 kg',
                '180.00',
                2,
            ],
            [
                'Encrier de bureau en bronze',
                'encrier-de-bureau-en-bronze',
                'Objets d\'art',
                'Encrier à deux godets en verre bleu, support en bronze à décor de feuilles de laurier. Plumier intégré.',
                'Bronze et verre',
                'XIXe siècle',
                'France',
                'Bronze et bleu',
                '28 × 16 × 9 cm',
                '2.4 kg',
                '145.00',
                3,
            ],
            [
                'Lustre en cristal de Bohême',
                'lustre-en-cristal-de-boheme',
                'Luminaires',
                'Lustre à huit branches, pampilles en cristal taillé, monture en bronze patiné. Électrifié aux normes actuelles.',
                'Cristal et bronze',
                'Restauration',
                'Tchécoslovaquie',
                'Transparent et doré',
                '⌀ 65 × 80 cm',
                '11 kg',
                '1450.00',
                1,
            ],
            [
                'Applique murale Art Déco',
                'applique-murale-art-deco',
                'Luminaires',
                'Applique à deux feux en bronze et verre moulé dépoli. Système électrique refait à neuf, aux normes NF C 15-100.',
                'Bronze et verre',
                'Art Déco',
                'France',
                'Bronze et ivoire',
                '36 × 12 × 28 cm',
                '2 kg',
                '380.00',
                2,
            ],
            [
                'Lampe à pétrole en opaline',
                'lampe-a-petrole-en-opaline',
                'Luminaires',
                'Lampe à pétrole à réservoir en opaline bleue, cheminée en verre moulé. Bec en laiton estampillé. Convertie à l\'électricité.',
                'Opaline et laiton',
                'Napoléon III',
                'France',
                'Bleu et laiton',
                '⌀ 18 × 52 cm',
                '2.6 kg',
                '210.00',
                2,
            ],
            [
                'Suspension en tôle émaillée',
                'suspension-en-tole-emaillee',
                'Luminaires',
                'Suspension d\'atelier en tôle émaillée blanche, bordure rouge. Câblage neuf, douille E27. Style industriel authentique.',
                'Tôle émaillée',
                'Industriel',
                'France',
                'Blanc et rouge',
                '⌀ 38 × 26 cm',
                '1.8 kg',
                '165.00',
                3,
            ],
            [
                'Bougeoir en bronze doré',
                'bougeoir-en-bronze-dore',
                'Luminaires',
                'Paire de bougeoirs à fût mouluré et base godronnée. Bronze doré d\'origine, très bel éclat.',
                'Bronze doré',
                'Louis XVI',
                'France',
                'Doré',
                '⌀ 10 × 24 cm',
                '1.4 kg',
                '130.00',
                4,
            ],
            [
                'Vase en faïence de Gien',
                'vase-en-faience-de-gien',
                'Céramiques',
                'Vase balustre à décor floral polychrome, bordure soulignée d\'un liseré bleu. Signature de la faïencerie sous la base.',
                'Faïence émaillée',
                'XIXe siècle',
                'France',
                'Bleu et blanc',
                '⌀ 22 × 38 cm',
                '2 kg',
                '145.00',
                3,
            ],
            [
                'Service de table en porcelaine',
                'service-de-table-en-porcelaine',
                'Céramiques',
                '36 pièces (assiettes, plats, saucière), décor de guirlandes dorées. Très rare service complet, aucune ébréchure.',
                'Porcelaine de Limoges',
                '1875-1890',
                'France',
                'Blanc et or',
                'Assiette : ⌀ 24 cm',
                '9 kg',
                '260.00',
                1,
            ],
            [
                'Plat en faïence de Rouen',
                'plat-en-faience-de-rouen',
                'Céramiques',
                'Plat ovale à décor de lambrequins bleus et de fleurettes, style rayonnant. Émail brillant, deux petits éclats au revers.',
                'Faïence stannifère',
                'XVIIIe siècle',
                'France (Rouen)',
                'Bleu et blanc',
                '38 × 28 cm',
                '1.5 kg',
                '420.00',
                1,
            ],
            [
                'Pot à tabac en grès émaillé',
                'pot-a-tabac-en-gres-emaille',
                'Céramiques',
                'Pot à tabac cylindrique à couvercle, émail moucheté brun. Objet utilitaire d\'époque, très décoratif sur une étagère.',
                'Grès émaillé',
                'XIXe siècle',
                'France',
                'Brun moucheté',
                '⌀ 14 × 18 cm',
                '1.1 kg',
                '75.00',
                5,
            ],
            [
                'Cache-pot en porcelaine de Paris',
                'cache-pot-en-porcelaine-de-paris',
                'Céramiques',
                'Cache-pot à décor de roses peintes à la main, rehaussé d\'or fin. Marque de manufacture en creux sous la base.',
                'Porcelaine',
                'XIXe siècle',
                'France',
                'Blanc et rose',
                '⌀ 18 × 16 cm',
                '0.9 kg',
                '190.00',
                2,
            ],
            [
                'Tapisserie d\'Aubusson',
                'tapisserie-d-aubusson',
                'Textiles & Tapisseries',
                'Tapisserie à décor de scène pastorale, encadrement de feuillages. Laine et soie, couleurs remarquablement fraîches.',
                'Laine et soie',
                'XVIIIe siècle',
                'France (Aubusson)',
                'Vert et ocre',
                '180 × 145 cm',
                '5 kg',
                '1650.00',
                1,
            ],
            [
                'Coussin en soie brodée',
                'coussin-en-soie-brodee',
                'Textiles & Tapisseries',
                'Coussin carré en soie ivoire, broderie de fleurs au point de tige. Garnissage neuf en plume, fermeture par boutons anciens.',
                'Soie brodée',
                'XIXe siècle',
                'France',
                'Ivoire et rose',
                '45 × 45 cm',
                '0.8 kg',
                '85.00',
                6,
            ],
            [
                'Nappe brodée en lin',
                'nappe-brodee-en-lin',
                'Textiles & Tapisseries',
                'Grande nappe en lin damassé, jour échelle et monogramme brodé. Linge de famille impeccable, sans tache ni accroc.',
                'Lin damassé',
                'Début XXe siècle',
                'France',
                'Écru',
                '300 × 180 cm',
                '1.6 kg',
                '65.00',
                4,
            ],
        ];

        foreach ($productsData as $data) {
            [
                $name, $slug, $categoryName, $description, $material, $style,
                $origin, $color, $size, $weight, $price, $stock
            ] = $data;

            $product = new Product();
            $product->setName($name);
            $product->setSlug($slug);
            $product->setCategory($categories[$categoryName]);
            $product->setDescription($description);
            $product->setMaterial($material);
            $product->setStyle($style);
            $product->setOrigin($origin);
            $product->setColor($color);
            $product->setSize($size);
            $product->setWeight($weight);
            $product->setPrice($price);
            $product->setStock($stock);
            $product->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($product);

            // ── 2 photos par produit (images de démonstration) ──
            foreach ([1, 2] as $i) {
                $image = new ProductImage();
                $image->setFilename($slug . '-' . $i . '.jpg');
                $image->setProduct($product);
                $product->addProductImage($image);
                $manager->persist($image);
            }
        }

        // Un seul flush : tout est inséré dans une transaction unique
        $manager->flush();
    }
}