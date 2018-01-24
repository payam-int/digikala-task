<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Variant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Created by PhpStorm.
 * User: payam
 * Date: 1/24/18
 * Time: 7:27 PM
 */
class ProductsAndVariantsFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $products = $this->getProducts();

        foreach ($products as $product) {
            $productObject = new Product($product['title'], $product['description']);
            $manager->persist($productObject);

            foreach ($product['variants'] as $variant) {
                $variantObject = new Variant($variant['price'], $variant['color'], $productObject);
                $manager->persist($variantObject);
            }
        }

        $manager->flush();
    }

    private function getProducts()
    {
        return [
            [
                "title" => "Human",
                "description" => "Modern humans (Homo sapiens, ssp. Homo sapiens sapiens) are the only extant members of the subtribe Hominina, a branch of the tribe Hominini belonging to the family of great apes. They are characterized by erect posture and bipedal locomotion; high manual dexterity and heavy tool use compared to other animals; and a general trend toward larger, more complex brains and societies.",
                "variants" => [
                    [
                        "color" => "Black",
                        "price" => "1999.99"
                    ],
                    [
                        "color" => "White",
                        "price" => "1999.99"
                    ],
                    [
                        "color" => "Brown",
                        "price" => "1999.99"
                    ]
                ]
            ],
            [
                "title" => "Plastic bag",
                "description" => "A plastic bag, polybag, or pouch is a type of container made of thin, flexible, plastic film, nonwoven fabric, or plastic textile. Plastic bags are used for containing and transporting goods such as foods, produce, powders, ice, magazines, chemicals, and waste. It is a common form of packaging.\nMost plastic bags are heat sealed together. Some are bonded with adhesives or are stitched.",
                "variants" => [
                    [
                        "color" => "Yellow",
                        "price" => "0.99"
                    ],
                    [
                        "color" => "White",
                        "price" => "0.99"
                    ],
                    [
                        "color" => "Black",
                        "price" => "0.99"
                    ]
                ]
            ],
            [
                "title" => "Pen",
                "description" => "A pen is a writing instrument used to apply ink to a surface, usually paper, for writing or drawing.[1] Historically, reed pens, quill pens, and dip pens were used, with a nib dipped in ink. Ruling pens allow precise adjustment of line width, and still find a few specialized uses, but technical pens such as the Rapidograph are more commonly used. Modern types include ballpoint, rollerball, fountain and felt or ceramic tip pens.",
                "variants" => [
                    [
                        "color" => "Black",
                        "price" => "0.99"
                    ],
                    [
                        "color" => "Red",
                        "price" => "0.99"
                    ],
                    [
                        "color" => "Blue",
                        "price" => "0.99"
                    ],
                    [
                        "color" => "Green",
                        "price" => "0.99"
                    ]
                ]
            ],
            [
                "title" => "Car",
                "description" => "A car (or automobile) is a wheeled motor vehicle used for transportation. Most definitions of car say they run primarily on roads, seat one to eight people, have four tires, and mainly transport people rather than goods.",
                "variants" => [
                    [
                        "color" => "Blue",
                        "price" => "9,999"
                    ],
                    [
                        "color" => "White",
                        "price" => "9,699"
                    ],
                    [
                        "color" => "Black",
                        "price" => "9,499"
                    ],
                    [
                        "color" => "Pink",
                        "price" => "9,499"
                    ],
                    [
                        "color" => "Red",
                        "price" => "9,499"
                    ]
                ]
            ]
        ];
    }
}
