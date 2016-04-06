#/bin/bash
echo "paste the link"
read link

folder=$PWD/videos/
livestreamer=""
ffmpeg=""

echo "play or download? (write low medium or high)"
read quality

php vootffmpeg.php "$link" "$folder" "$livestreamer" "$quality" "$ffmpeg"
fi
