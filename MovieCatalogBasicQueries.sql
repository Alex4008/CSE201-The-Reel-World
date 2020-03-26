USE MovieCatalog
GO

--To sort all movies by name
SELECT * FROM Movies m
ORDER BY m.title;

--To sort all movies by rating descendingly
SELECT * FROM Movies m
ORDER BY rating DESC;

--To filter movies by genre
SELECT m.*, g.description 
FROM Movies m, Genres g, GenreMovie gm
WHERE (gm.genreId = g.genreId) AND (gm.movieId = m.movieId) AND (g.description = 'Romance')
--ORDER BY m.title (optional, this will order movies with genre Romance by titles)
--ORDER BY m.rating DESC (optional, this will order movies with genre Romance by rating descendingly)

--To filter movies by keywords
SELECT m.*
FROM Movies m
WHERE m.keywords LIKE '%love%'
--ORDER BY m.title (optional, this will order movies with genre Romance by titles)
--ORDER BY m.rating DESC (optional, this will order movies with genre Romance by rating descendingly)

--To filter movies by actors
SELECT m.*, a.actorName 
FROM Movies m,Actors a, ActorMovie am
WHERE (am.actorId = a.actorId) AND (am.movieId = m.movieId) AND (a.actorName = 'Julia Roberts')
--ORDER BY m.title (optional, this will order movies with genre Romance by titles)
--ORDER BY m.rating DESC (optional, this will order movies with genre Romance by rating descendingly)