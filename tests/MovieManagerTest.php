<?php
use PHPUnit\Framework\TestCase;

// Load passwords
require_once('db.php');

final class MovieManagerTest extends TestCase
{
    // Testing if MovieManager object can be created 
    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(
            MovieManager::class,
            new MovieManager($GLOBALS['mysqli'])
        );
    }
    
    // Testing if getAllMovies() returns more than zero movies
    // Assumes there is at least 1 movie in database 
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
    
    // Testing if getAllMoviesByKeyword() returns movies that match keyword
    // Assumes that there is at least one movie with keyword 'man'
    public function testCanGetAllMoviesByKeyword(): void
    {
        $mm = new MovieManager($GLOBALS['mysqli']);
        $statement = $mm->getAllMoviesByKeyword('man');
        $result = $statement->store_result();
        
        $this->assertGreaterThan(
            0,
            $statement->num_rows
        );
    }
    
    // Testing if getAllMoviesByRating() returns more than zero movies
    // Assumes there is at least 1 movie in database
    public function testCanGetAllMoviesByRating(): void
    {
        $mm = new MovieManager($GLOBALS['mysqli']);
        $statement = $mm->getAllMoviesByRating();
        $result = $statement->store_result();
        
        $this->assertGreaterThan(
            0,
            $statement->num_rows
        );
    }
    
    // Testing if getAllGenres() returns all 24 genres
    // Assumes there are 24 genres in the database  
    public function testCanGetAllGenres(): void
    {
        $mm = new MovieManager($GLOBALS['mysqli']);
        $statement = $mm->getAllGenres();
        $result = $statement->store_result();
        
        $this->assertEquals(
            24,
            $statement->num_rows
        );
    }
    
    // Testing if getAllActors() returns more than 0 actors
    // Assumes there is at least 1 actor in the database   
    public function testCanGetAllActors(): void
    {
        $mm = new MovieManager($GLOBALS['mysqli']);
        $statement = $mm->getAllActors();
        $result = $statement->store_result();
        
        $this->assertGreaterThan(
            0,
            $statement->num_rows
        );
    }
    
    // Testing if getCheckedGenres() can get movies of a genre when movies of that genre exist
    // Assumes there are 3 movies with genre 'romance' 
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
    
//    // Testing if getCheckedGenres() can get movies of a genre when movies of that genre do not exist
//    // Assumes there are 0 movies with genre 'fantasy' -- test not necessary -- assumption false
//    public function testCanGetNotMatchedGenres(): void
//    {
//        $mm = new MovieManager($GLOBALS['mysqli']);
//        $statement = $mm->getCheckedGenres(array('fantasy'));
//        $result = $statement->store_result();
//        
//        $this->assertEquals(
//            0,
//            $statement->num_rows
//        );
//    }
    
    // Testing if getSingleMovie() returns exactly one movie
    // Assumes there is a movie with title 'cleopatra' 
    public function testCanGetSingleMovie(): void
    {
        $mm = new MovieManager($GLOBALS['mysqli']);
        $statement = $mm->getSingleMovie('cleopatra');
        $result = $statement->store_result();
        
        $this->assertEquals(
            1,
            $statement->num_rows
        );
    }
    
    // Testing if addMovie() adds a movie to the database  
    public function testCanAddMovie(): void
    {
//        $mm = new MovieManager($GLOBALS['mysqli']);
//        $statement = $mm->addMovie('cleopatra');
//        $result = $statement->store_result();
//        
//        $this->assertEquals(
//            1,
//            $statement->num_rows
//        );
    }
}
?>