<?php
    interface BaseModel {
        public function insert(array $data): int | null;
        public function findById(int $id): stdClass | null;
        public function readAll(): array | null;
        public function update(int $id, array $class): bool;
        public function delete(int $id): bool;
        public function search(string $field, string $searchType, string $searchString): array | null;
        public function exists(string $field, string $fieldValue): bool;
    }
?>