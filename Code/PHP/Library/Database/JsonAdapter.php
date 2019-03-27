<?php
declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: mgbs
 * Date: 29.01.17
 * Time: 19:30
 */

namespace mgbs\Library\Database;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class JsonAdapter implements DatabaseAdapterInterface
{

    /**
     * @var String $filename
     */
    private $filename;

    /** @var  \PDO $pdo */
    private $pdo;
    /**
     * @var String
     */
    private $sqliteFileToDumpFlatFileIn;

    public function __construct(String $filename, String $sqliteFileToDumpFlatFileIn)
    {
        $this->filename = $filename;
        $this->sqliteFileToDumpFlatFileIn = $sqliteFileToDumpFlatFileIn;
        $this->initPdoInstance();
        $this->truncateQuestionTable();
        $this->loadFileIntoDatabase();
    }

    /**
     * Initializes the PDO instance
     *
     * @return void
     * @throws \RuntimeException
     * @throws \Symfony\Component\Filesystem\Exception\FileNotFoundException
     */
    public function initPdoInstance()
    {
        if (!file_exists($this->filename)) {
            throw new FileNotFoundException('Databasefile ' . $this->filename
                . ' not found. Current BaseDir:' . __DIR__);
        }

        if (!file_exists($this->sqliteFileToDumpFlatFileIn)) {
            throw new FileNotFoundException('SqliteFile ' . $this->sqliteFileToDumpFlatFileIn
                . ' not found. Current BaseDir:' . __DIR__);
        }

        try {
            $this->pdo = new \PDO('sqlite:' . $this->sqliteFileToDumpFlatFileIn);
        } catch (\Exception $e) {
            throw new \RuntimeException('Cannot connect to database: ' . $e->getMessage());
        }
    }

    /**
     * loads the json into the memory db
     * @throws \UnexpectedValueException
     */
    private function loadFileIntoDatabase()
    {
        $jsonAsObject = $this->readJsonAsObject();
        $this->createGameTables();

        $insertTableData = 'INSERT INTO "questions" (category, points, answer, question)
                            VALUES (:category, :points, :answer, :question)';

        $insertDataStatement = $this->pdo->prepare($insertTableData);
        foreach ($jsonAsObject as $singleRow) {
            $insertDataStatement->bindValue(':category', $singleRow->category);
            $insertDataStatement->bindValue(':points', $singleRow->points);
            $insertDataStatement->bindValue(':answer', $singleRow->answer);
            $insertDataStatement->bindValue(':question', $singleRow->question);
            $insertDataStatement->execute();
        }
    }

    /**
     * @return \PDO
     */
    public function getPdoInstance(): \PDO
    {
        return $this->pdo;
    }

    /**
     * Returns stdClass of StdClass Object
     * @return array
     * @throws \UnexpectedValueException
     */
    private function readJsonAsObject(): array
    {
        $json = json_decode(file_get_contents($this->filename));
        if (null === $json) {
            throw new \UnexpectedValueException('Json is either malformed, or empty');
        }

        if (!\is_array($json->questions) || !is_a($json, \stdClass::class)) {
            throw new \UnexpectedValueException('Could not read database file');
        }
        return $json->questions;
    }

    /**
     * Creates table in memory db
     */
    private function createGameTables(): void
    {
        $createQuestionsTable = '
        CREATE TABLE "questions" (
                	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                	`category`	TEXT DEFAULT \' \',
                	`points`	INT DEFAULT 0,
                	`answer`	TEXT DEFAULT \' \',
                	`question`	TEXT DEFAULT \' \'
                )';

        $this->pdo->exec($createQuestionsTable);

        $createGameEventsTable = '
        CREATE TABLE "GameEvents" ( 
                    `question_id` INTEGER NOT NULL, 
                    `buzzer_id` INTEGER, 
                    `correct_or_false` INTEGER, 
                    `game_event_id` INTEGER PRIMARY KEY AUTOINCREMENT, 
                    `question_closed` INTEGER DEFAULT 0 )';

        $this->pdo->exec($createGameEventsTable);
    }

    private function truncateQuestionTable(): void
    {
        $truncateQuestionTableSql = 'DELETE FROM questions';

        $this->pdo->exec($truncateQuestionTableSql);
    }
}
