<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Book;
use App\Entity\Author;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        ini_set('memory_limit', '9999M');
        for ($i = 0; $i < 10000; $i++) {

            $book = new Book();
            $book->translate('ru')->setName("Самая лучшая книга #" . ($i + 1));
            $book->translate('en')->setName("Best of the best book #" . ($i + 1));
            for ($j = 0; $j < random_int(1, 3); $j++) {
                $author = new Author();
                $author->setName(sprintf(($j + 1) . "ый(ой) Автор лучшей книги #" . ($i + 1)));
                $manager->persist($author);
                $book->addAuthors($author);
            }
            $manager->persist($book);
            $book->mergeNewTranslations();
        }

        $manager->flush();
    }
}
