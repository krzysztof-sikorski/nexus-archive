<?php

declare(strict_types=1);

namespace App\DTO\Nexus\Leaderboard;

use App\Contract\Entity\Nexus\Leaderboard\EntryInterface;
use App\Contract\Entity\Nexus\Leaderboard\EntryListInterface;

use InvalidArgumentException;

use function array_key_exists;
use function count;
use function is_int;

class EntryList implements EntryListInterface
{
    private ?int $currentPosition = null;
    private array $entries = [];

    public function current(): ?EntryInterface
    {
        if ($this->valid()) {
            return $this->entries[$this->currentPosition];
        }
        return null;
    }

    public function key(): ?int
    {
        return $this->currentPosition;
    }

    public function next(): void
    {
        ++$this->currentPosition;
    }

    public function rewind(): void
    {
        if (count($this->entries) > 0) {
            $this->currentPosition = 0;
        } else {
            $this->currentPosition = null;
        }
    }

    public function valid(): bool
    {
        return array_key_exists(key: $this->currentPosition, array: $this->entries);
    }

    public function count(): int
    {
        return count($this->entries);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists(key: $offset, array: $this->entries);
    }

    public function offsetGet(mixed $offset): ?EntryInterface
    {
        if (array_key_exists(key: $offset, array: $this->entries)) {
            return $this->entries[$offset];
        }
        return null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (false === is_int(value: $offset)) {
            throw new InvalidArgumentException(message: 'Offset is not an integer');
        }
        if (false === $value instanceof EntryInterface) {
            throw new InvalidArgumentException(message: 'Value is not an EntryInterface');
        }
        $this->entries[$offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        if (array_key_exists(key: $offset, array: $this->entries)) {
            unset($this->entries[$offset]);
        }
    }
}
