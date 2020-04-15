<?php
use PHPUnit\Framework\TestCase;

// Load passwords
require_once('db.php');

final class MovieManagerTest extends TestCase
{
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            MovieManager::class,
            new MovieManager($GLOBALS['mysqli'])
        );
    }
    
    public function testCanGetAllMovies(): void
    {
        $mm = new MovieManager($GLOBALS['mysqli']);
        $statement = $mm->getAllMovies();
        $result = $statement->store_result();
        
        $this->assertGreaterThan(
            0,
            $statement->num_rows
        );
    }
    
    public function testCanGetAllGenres(): void
    {
        $mm = new MovieManager($GLOBALS['mysqli']);
        $statement = $mm->getAllGenres();
        $result = $statement->store_result();
        
        $this->assertEquals(
            20,
            $statement->num_rows
        );
    }
    
    public function testCanGetMatchedGenres(): void
    {
        $mm = new MovieManager($GLOBALS['mysqli']);
        $statement = $mm->getCheckedGenres(array('romance'));
        $result = $statement->store_result();
        
        $this->assertEquals(
            3,
            $statement->num_rows
        );
    }
    
    public function testCanGetNotMatchedGenres(): void
    {
        $mm = new MovieManager($GLOBALS['mysqli']);
        $statement = $mm->getCheckedGenres(array('fantasy'));
        $result = $statement->store_result();
        
        $this->assertEquals(
            0,
            $statement->num_rows
        );
    }
}
?>