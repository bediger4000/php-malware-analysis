#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <errno.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <unistd.h>
#include <ctype.h>

/* Byte-wise XOR decoding of a stream of bytes. */

int
main(int ac, char **av)
{
	FILE *fin = stdin;
	char *keystring;
	int keylen;

	if (ac < 3)
	{
		fprintf(stderr, "xor - decode xor-encoded files, where key is a text string\n");
		fprintf(stderr, "Usage: xor <filename> <keystring>\n");
		fprintf(stderr, "Decoded output on stdout\n");
		exit(1);
	}

	keystring = av[2];
	keylen = strlen(keystring);

	if (strcmp(av[1], "-"))
	{
		if (NULL == (fin = fopen(av[1], "r")))
		{
			fprintf(stderr, "Could not fopen(%s) for read: %s\n",
				av[1], strerror(errno));
			exit(3);
		}
	}

	int i, c;
	for (i = 0; (c = fgetc(fin)) != EOF; ++i)
	{
		fputc((0xff & c) ^ keystring[i % keylen], stdout);
	}

	if (fin != stdin) fclose(fin);

	return 0;
}
