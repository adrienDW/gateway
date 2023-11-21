<?php

namespace App\Service;

interface SearchGamesInterface {
    public function searchByName(string $name): array;
    public function searchById(int $id): array;
}