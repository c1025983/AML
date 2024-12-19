<?php
use PHPUnit\Framework\TestCase;

//REFERENCES:
//https://www.freecodecamp.org/news/test-php-code-with-phpunit/

//Ive recreated the tables in this test file so that this can be tested independently

class TaiyebUnitTesting extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Setup in-memory SQLite database for testing
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create tables
        $this->pdo->exec("
            CREATE TABLE mediaitem (
                media_id INTEGER PRIMARY KEY,
                title TEXT,
                availability INTEGER
            );

            CREATE TABLE borrowrecord (
                record_id INTEGER PRIMARY KEY AUTOINCREMENT,
                borrow_date TEXT,
                return_due TEXT,
                status TEXT,
                member_id INTEGER,
                media_id INTEGER,
                FOREIGN KEY (media_id) REFERENCES mediaitem (media_id)
            );
        ");

        //This is a test using dummy data to soley test if the SQL works
        $this->pdo->exec("
            INSERT INTO mediaitem (media_id, title, availability) VALUES
            (1, 'Test Book', 1);
        ");
    }


    //This creates a table soley for testing purposes
    public function testBorrowRecordCreation()
    {
        //
        $mediaId = 1;
        $memberId = 123;
        $borrowDate = date('Y-m-d');
        $returnDue = date('Y-m-d', strtotime('+14 days'));

        // When: Insert a borrow record
        $stmt = $this->pdo->prepare("
            INSERT INTO borrowrecord (borrow_date, return_due, status, member_id, media_id)
            VALUES (:borrow_date, :return_due, 'borrowed', :member_id, :media_id)
        ");
        $stmt->execute([
            ':borrow_date' => $borrowDate,
            ':return_due' => $returnDue,
            ':member_id' => $memberId,
            ':media_id' => $mediaId,
        ]);

        //Check if the borrowrecord is present in the database or not
        $stmt = $this->pdo->prepare("SELECT * FROM borrowrecord WHERE media_id = :media_id");
        $stmt->execute([':media_id' => $mediaId]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotNull($record, 'Borrow record should exist');
        $this->assertEquals($borrowDate, $record['borrow_date'], 'Borrow date should match');
        $this->assertEquals($returnDue, $record['return_due'], 'Return due date should match');
        $this->assertEquals('borrowed', $record['status'], 'Status should be borrowed');
        $this->assertEquals($memberId, $record['member_id'], 'Member ID should match');
        $this->assertEquals($mediaId, $record['media_id'], 'Media ID should match');
    }

    protected function tearDown(): void
    {
        $this->pdo = null; 
    }
}
