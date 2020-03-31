DROP DATABASE IF EXISTS MovieCatalog;
GO

CREATE DATABASE MovieCatalog;
GO

USE MovieCatalog;
GO

CREATE TABLE Users(
	userId			INT				NOT NULL	IDENTITY	PRIMARY KEY,
	userName		VARCHAR(50)		NOT NULL,
	[password]		VARCHAR(100)	NOT NULL,
	displayName		VARCHAR(200),
	isDeleted		BIT				NOT NULL	DEFAULT(0),
);

CREATE TABLE Genres(
	genreId			INT				NOT NULL	IDENTITY	PRIMARY KEY,
	[description]	VARCHAR(200)	NOT NULL,
	isDeleted		BIT				NOT NULL	DEFAULT(0)
);

CREATE TABLE Requests(
	requestId			INT				NOT NULL	IDENTITY	PRIMARY KEY,
	userId				INT				NOT NULL	FOREIGN KEY REFERENCES Users(userId),
	requestDate			DATETIME		NOT NULL	DEFAULT(GETDATE()),
	requestName			VARCHAR(100),
	[description]		VARCHAR(300)
);

CREATE TABLE Actors(
	actorId					INT				NOT NULL	IDENTITY	PRIMARY KEY,
	requestId				INT				NOT NULL	FOREIGN KEY REFERENCES Requests(requestId),
	actorName				VARCHAR(100)	NOT NULL,
	actorLink				VARCHAR(2000),
	isDeleted				BIT				NOT NULL	DEFAULT(0)
);

CREATE TABLE Movies(
	movieId					INT				NOT NULL	IDENTITY	PRIMARY KEY,
	requestId				INT				NOT NULL	FOREIGN KEY REFERENCES Requests(requestId),
	title					VARCHAR(200)	NOT NULL,
	[description]			VARCHAR(3000),
	keywords				VARCHAR(2000),
	imdbLink				VARCHAR(3000),
	[image]					VARBINARY(500),
	imageAddress			VARCHAR(3000),
	rating					FLOAT,
	isDeleted				BIT				NOT NULL	DEFAULT(0)
);

CREATE TABLE Comments(
	commentId			INT				NOT NULL	IDENTITY	PRIMARY KEY,
	userId				INT				FOREIGN KEY REFERENCES Users(userId),
	movieId				INT				NOT NULL	FOREIGN KEY REFERENCES	Movies(movieId),
	commentText			VARCHAR(3000)	NOT NULL
);

CREATE TABLE ActorMovie(
	actorMovieId		INT				NOT NULL	IDENTITY	PRIMARY KEY,
	actorId				INT				NOT NULL	FOREIGN KEY REFERENCES	Actors(actorId),
	movieId				INT				NOT NULL	FOREIGN KEY REFERENCES	Movies(movieId),
);

CREATE TABLE GenreMovie(
	genreMovieId		INT				NOT NULL	IDENTITY	PRIMARY KEY,
	genreId				INT				NOT NULL	FOREIGN KEY REFERENCES	Genres(genreId),
	movieId				INT				NOT NULL	FOREIGN KEY REFERENCES	Movies(movieId),
);