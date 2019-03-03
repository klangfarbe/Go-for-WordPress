# SGF-Viewer Wordpress plugin

**Important: This plugin is not maintained actively since I do not use WordPress anymore for any of my websites. Still it should be working can be used as is.**

This plugin makes use of the great JavaScript library [WGo.js](http://wgo.waltheri.net) to allow a WordPress installation to upload and display Kifus. Some of the key features are:

* Interactive display of SGF files including variations and comments
* Display of a dedicated position of an SGF file as a static diagram
* Mutiple available board designs
* Responsive design of the Kifu
* Usage of modern HTML5 Canvas to draw the images
* Create player tables by querying the European Go Database

##  Usage

The plugin adds two new shortcodes to the WordPress installation:

* `[wgo]...[/wgo]` and
* `[egd]`.

Design settings can be found in the admin menu *Design -> Go, Baduk, Weiqi*

### The WGO shortcode

Usage: `[wgo]URL or SGF[/wgo]`

It is possible to either use an URL to load the SGF-file _from your site_ or to type in the SGF directly inside the shortcode block.

#### Example 1: Loading a Kifu

`[wgo]http://.../ear-reddening-game.sgf[/wgo]`

**Important:** Please note that it is not possible to load a SGF-file from a remote server due to security reasons.

#### Example 2: Displaying a position as a static image

```
[wgo static="true" limit="0,9,12,0" maxwidth="250px" float="left"]
(;GM[1]
    AW[fc][hd][ge][he]
    AB[dc][ic][id][ce][fe][gf]
    LB[hd:1][id:2][ge:3][he:5][gf:4]TR[ff])
[/wgo]
```

### Allowed options for the wgo shortcode

Several options are available in the settings and are used by the plugin as default values. The following options can be used inside the `[wgo]`-shortcode:

* **`width`**
    Specify the width in **px** or **%** of the rendered image

* **`maxwidth`**
    The maximum width of the diagram. Usually this should be a value in % but can also be px, e.g. `[wgo width="75%" maxwidth="400px"]...[/wgo]`

* **`stones`**
    Defines the look of the stones. Possible values are `NORMAL`, `GLOW` or `MONO`

* **`background`**
    The filename of the background image or the color to use (RGB), e.g. `#33ff88`

* **`move`**
    The number of initially displayed moves. In case variations should be displayed it is mandatory to specify at first the number, followed by move which contains the variation and the number of the variation (starting with zero). For example the string `m:100,50:0,75:1` means that the first 100 moves should be displayed, at move 50 use the main branch (0) and at move 75 use variation 1

* **`static`**
    If **true** disable all interactive elements of the board and draw it as a static diagram

* **`limit`**
    Do not display the whole board but define the part which should be drawn. The format is *top, right, bottom, left*, e.g. `limit="0,9,12,0`:
    ```
    [wgo static="true" limit="0,9,12,0" maxwidth="250px"]
    (;GM[1]
        AW[fc][hd][ge][he]
        AB[dc][ic][id][ce][fe][gf]
        LB[hd:1][id:2][ge:3][he:5][gf:4]TR[ff])
    [/wgo]
    ```

* **`float`**
    As in html the float can be used to make the diagram floating `left` or `right`

**Imporant:** The plugin-settings have an option to specify if floating should be disabled in case the screen width is smaller than a given value.

### The EGD-shortcode

The second shortcode added by this plugin can be used to create a table with player information queried from the European Go Database using its webservice. To create the list the player ID from the EGD is added and additionally a list of columns to display:

`[egd players="9999,7777,5555" fields="name,declared,gor2,club,cc,link"]`

The following fields can be selected:

* **`name`**
    Players name

* **`declared`**
    The self declared rank of the player

* **`rank`**
    The rank based on the _Go Rank_ (gor)

* **`gor1`**
    The numeric _Go Rank_

* **`gor2`**
    The numeric _Go Rank_ including the human readable value (Dan/Kyu)

* **`club`**
    The players main club

* **`cc`**
    The players country

## Download and Installation

The easiest way to install is via the plugin manager of WordPress. Search for the keyword _sgf_ and you should find it. If you want to install manually, the latest stable version can be downloaded from the [Git repository](https://github.com/klangfarbe/Go-for-WordPress) or the [WordPress Plugin Page](http://wordpress.org/plugins/go-baduk-weiqi/ "Plugin Page at WordPress.org") and must be extracted inside the plugin folder of your WordPress installation (usually `/wp-content/plugins`).

## Support

As mentioned above, the plugin is not maintained actively anymore and can be used as is. But feel free to fork and create a pull request.
