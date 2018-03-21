# dsfr
Damn Small File registry

## Usage

Send a post request with a "file" element. Will return a JSON responce with an UID to reach the file back. For testing only. Not safe.

Exemple:

**Adding content**

```
<form action="index.php" method="post" enctype="multipart/form-data">
	<input name="upload[]" type="file" multiple="multiple" id="upload" /><br />
	<input name="key" type="text" value="abc" /><br />
	<input type="submit" name="s" value="send" />
</form>
```

This will return a JSON encoded response with an UID.

**Pulling Content**

```
<img src="index.php?q=[the_UID]" />
```
