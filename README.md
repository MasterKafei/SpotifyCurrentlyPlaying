# SpotifyCurrentlyPlaying

## Introduction
SpotifyCurrentlyPlaying can automatically record the information of the music being listened to.
Useful for streamers who want to credit artists on their overlays.

It is possible to display the name of the music, the title and the cover of the album and their artists.

Only works for online music. Listening to music locally will not work.

## Setup

### Installation
This project was developed with PHP 7.4, Composer and Symfony. It is therefore necessary to install it to use this project.

Spotify will communicate the access code through HTTP Request. It is therefore necessary to have a web server such as Apache or Nginx to retrieve this access code. Personally I use Symfony's internal server.

You will need to execute ```composer install``` in project's root folder to install every dependency

### Spotify Application

Connecting to the Spotify API requests login credentials to a Spotify app. It is necessary to create a Spotify app to use this project. You can create one at this link: https://developer.spotify.com/dashboard/login. For the redirect url you will need to define ```http://localhost/callback``` (the localhost host name can be whatever value you have configured with your web server address)

### Configuration

Once your Spotify's app is created, you can indicate your client id as well as your client secret in the .env file of the project.

#### File informations 

To indicate where and in what format to save the information of the current music, it is necessary to create a file ```information_files.yaml``` at the root of the project.

#### Syntax

Your yaml file must start with the ```files``` keyword and every element in this array will be a unique file containing current music information.
A **file** will containt different keys to indicate how to retreive and save information.
You can use 4 keys:
  - pattern:  ```string``` - define the content of the file. You can't use ```%title%```, ```%album%```, ```%artists%``` (or ```%artist1%```, ```%artist2%```, ```%artist3%``` ...)
  - path: ```string``` - define the location of the path which will containt music information. It can be relative or absolute
  - limit: ```int``` - define the max length of the result of a pattern. If the containt of the file reach the limit all excess characters will be replaced by ```...```
  - album_image: ```bool``` - indicate if it's the album image. (Define this option to true will disable the effect of ```pattern``` and ```limit``` option in this file)
  
  
#### Example

```
files:
    - pattern: '%title% '   
      path: 'D:\Desktop\Spotify Currently Playing\Title.txt'
      limit: 40

    - pattern: '%artists% '
      path: 'D:\Desktop\Spotify Currently Playing\Artist.txt'

    - pattern: '%album% '
      path: 'D:\Desktop\Spotify Currently Playing\Album.txt'

    - album_image: true
      path: 'D:\Desktop\Spotify Currently Playing\Current Album Image.png'
```

## First launch (and every other launch after long time of inactivity)

Start your web server and check that the website address you entered on the Spotify dashboard is the one that redirects to the project.

Run the command ```php bin/console app:access_code``` in SpotifyCurrentlyPlaying to get the user access code. Once Spotify redirects you to the project page, you can shut down the web server.

## Every other launch

Execute the command ```php bin/console app:start``` to start writing music information to the files you created.
