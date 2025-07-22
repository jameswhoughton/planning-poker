<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\DB;

trait RefreshMongoDatabase
{
    public function refreshMongoDatabase(): void
    {
        $connection = DB::connection('mongodb');
        $database = $connection->getMongoDB(); // Gets native MongoDB database object

        $collections = $database->listCollections();

        foreach ($collections as $collection) {
            $name = $collection->getName();

            // Skip system collections like system.indexes
            if (str_starts_with($name, 'system.')) {
                continue;
            }

            // Clear the collection
            $database->$name->deleteMany([]);
        }
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshMongoDatabase();
    }
}
