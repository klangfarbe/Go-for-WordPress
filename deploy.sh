#!/bin/bash
#
# A modification of Dean Clatworthy's deploy script as found here:
# 	https://github.com/deanc/wordpress-plugin-git-svn
#
# The difference is that this script lives in the plugin's git repo & doesn't
# require an existing SVN repo. It also adds multiple sanity checks before
# creating a new release

# main config (adapt to your needs)
SVNUSER="klangfarbe"
PLUGINSLUG="go-baduk-weiqi"
MAINFILE="go.php" # this should be the name of your main php file in the wordpress plugin

# svn settings
SVNPATH="/tmp/$PLUGINSLUG"
SVNURL="http://plugins.svn.wordpress.org/${PLUGINSLUG}/"

# ------------------------------------------------------------------------------

function cleanup() {
	echo "Removing temporary directory $SVNPATH"
	cd $CURRENTDIR
	rm -fr $SVNPATH/

	echo "Switching back to master branch"
	git checkout master

	echo "Removing deployment branch"
	git branch -D dist_$NEWVERSION1
}

# ------------------------------------------------------------------------------
# Let's begin...
# ------------------------------------------------------------------------------
echo ".........................................."
echo
echo "Preparing to deploy wordpress plugin"
echo
echo ".........................................."
echo

# change to git top level dir
cd $(git rev-parse --show-toplevel)
CURRENTDIR=`pwd`

# Check version in readme.txt is the same as plugin file
NEWVERSION1=`grep "^Stable tag" readme.txt | awk -F' ' '{print $3}'`
NEWVERSION2=`grep -E "^[[:space:]*]*Version" $MAINFILE | sed -e 's/.*Version:\s*//'`
echo "readme version: $NEWVERSION1"
echo "$MAINFILE version: $NEWVERSION2"

if [ "$NEWVERSION1" != "$NEWVERSION2" ]; then
	echo "Versions don't match. Exiting...."
	exit 1
fi

echo "Versions match in readme.txt and PHP file. Let's proceed..."

# drop out if there are dirty files in the workspace
if ! git diff-files --quiet --ignore-submodules; then
	echo "Workspace is dirty, please stash changes first. Exiting...."
	exit 1
fi

# drop out if there are uncommited changes
if ! git diff-index --quiet --ignore-submodules HEAD; then
	echo "There are uncommitted changes in the workspace. Exiting...."
	exit 1
fi

# check if there is already a tag for this version, if yes abort
if git show-ref --quiet --tags $NEWVERSION1; then
	echo "Version $NEWVERSION1 is already published. Exiting...."
	exit 1
fi

echo "Tagging new version in git"
git tag -a "$NEWVERSION1" -m "Version $NEWVERSION1"

# ------------------------------------------------------------------------------
# Sanity checks are done, create the temporary SVN repo and build the release
# ------------------------------------------------------------------------------
echo
echo "Creating local copy of SVN repo ..."
svn co $SVNURL $SVNPATH

echo "Switching branch to version $NEWVERSION1"
git checkout -b dist_${NEWVERSION1} $NEWVERSION1

echo "Exporting index from git to the trunk of SVN"
git checkout-index -a -f --prefix=$SVNPATH/trunk/

echo "Switching to SVN repository"
cd $SVNPATH

echo "Ignoring github specific & deployment script"
svn propset svn:ignore "deploy.sh
README.md
.git
.gitignore" "trunk/"

if [ -d trunk/assets-wp-repo ]; then
	echo "Moving assets-wp-repo"
	mv trunk/assets-wp-repo/* assets/
	svn add assets/
	svn delete trunk/assets-wp-repo
fi

echo "Changing directory to trunk"
cd $SVNPATH/trunk/

# only commit if there are changes
if [ -z "`svn status`" ]; then
	echo "No changes found for this release found. Existing..."
	cleanup
	exit 1
fi

echo "Adding all new files that are not set to be ignored"
svn status | grep -v "^.[ \t]*\..*" | grep "^?" | awk '{print $2}' | xargs svn add

echo "Committing to trunk"
svn commit --username=$SVNUSER -m "Releasing files for version $NEWVERSION1"

echo "Updating WP plugin repo assets & committing"
cd $SVNPATH/assets/
svn commit --username=$SVNUSER -m "Updating wp-repo-assets"

echo "Creating new SVN tag & committing it"
cd $SVNPATH
svn copy trunk/ tags/$NEWVERSION1/

cd tags/$NEWVERSION1
svn commit --username=$SVNUSER -m "Tagging version $NEWVERSION1"

cleanup

echo "*** FIN ***"