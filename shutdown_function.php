<?php

echo "$hello";

print_r(error_get_last());

function shutdown()
{
	echo "end";
}

register_shutdown_function('shutdown');
