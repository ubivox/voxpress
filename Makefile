release:
	hg archive /tmp/voxpress
	cp -Ra /tmp/voxpress/plugin/voxpress/* ~/projects/wordpress-voxpress/trunk/
