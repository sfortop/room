<?php
/**
 * room
 *
 * @author Serhii Borodai <clarifying@gmail.com>
 */

return [
    'hydrator' => [
        // set false on production environment
        'regenerate-always' => true,
        'path' => realpath(__DIR__) . '/../generated/hydrator',
        // list of FQN class names or namespaces which require generated hydrators
        'classes' => [
            \App\Entity\PriceInterval::class,
            \App\Entity\CorrectStorage::class,
        ],
    ],

];