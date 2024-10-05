<?php

declare(strict_types=1);

namespace App\Todos\Domain\Entities;

class TodoEntity
{
    private string $id;
    private string $title;
    private string $description;
    private bool $status;
    private string $createdAt;
    private string $updatedAt;
    private string $dueDate;

    public function __construct(string $id, string $title, string $description, bool $status, string $createdAt, string $updatedAt, string $dueDate)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->dueDate = $dueDate;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): bool
    {
        return $this->status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getDueDate(): string
    {
        return $this->dueDate;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'due_date' => $this->dueDate,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'],
            $data['title'],
            $data['description'],
            $data['status'],
            $data['created_at'],
            $data['updated_at'],
            $data['due_date']
        );
    }
}