Go for WordPress
================

This plugin makes use of the great [wgo.js](http://wgo.waltheri.net) library and adds two new shortcodes to wordpress which allows users to display SGF files and build player tables by querying the European Go Database.

## Installation

Download the package and extract it to you Wordpress plugin folder.

## The shortcodes

### Adding Kifus

You can load a Kifu by adding the URL to the sgf file. Please note that the file must be on the same server as the WordPress installation.

    [wgo-sgf]http://url/to/file.sgf[/wgo-sgf]

Or you can put the sgf text directly into the shortcode:

    [wgo-sgf static="true" limit="0,9,12,0" maxwidth="250px"]
    (;GM[1]
     AW[fc][hd][ge][he]
     AB[dc][ic][id][ce][fe][gf]
     LB[hd:1][id:2][ge:3][he:5][gf:4]TR[ff])
    [/wgo-sgf]

#### Parameters

width
:    The width in px or percent

maxwidth
:	   The maximum width of the diagram

stones
:	   Defines the stone renderer. Allowed values are __NORMAL__, __GLOW__ and __MONO__

background
:    The filename of the background image (must be stored in the _img_ subfolder of the plugin) or the RGB color

move
:    Display the first _m_ moves. If variations should be used, a JSON-string must be given which contains the key _m_ to show the first _m_ moves and for every variation, the move number as key and the variation as value, e.g. __{m: 100, 50: 0, 75: 1}__ means _"show the first 100 moves, in move 50 use the first and in move 75 the second variation"_

static
:    If set to _true_, the board will be rendered as a static image instead of enabling and showing all the controls supported by [wgo.js](http://wgo.waltheri.net)

limit
:    Defines a section to display from the whole board in the form __top,right,bottom,left__, e.g. __limit="0,9,12,0"__

float
:    Could be __left__ or __right__

### Adding a player table

The second shortcode can be used to create a table of players and some information about them as stored in the [European Go Database](http://europeangodatabase.eu). Table rows are ordered by _GoR_.

    [egd players="player_pin1, player_pin2, ..."]

The following columns are available to display:

name
:    The first and last name of the player

declared
:    The rank declared by the player itself

rank
:    The calculated rank based on the GoR

gor1
:    The _Go Rank (GoR)_ which is a numeric value like the ELO

gor2
:    Same as _gor1_ but including the _rank_ in parenthesis

club
:    The club a player belongs to

cc
:    The country code of the player

link
:    Generates a link to the players EGD entry

#### Parameters

By default the following columns are displayed:

    name,declared,gor2,club,cc,link'

If you want to define a different set of fields, you can use the __fields__ parameter:

    [egd fields="gor1,name" players="player_pin1, player_pin2, ..."]
