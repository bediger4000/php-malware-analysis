#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <errno.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <unistd.h>
#include <ctype.h>

/* Byte-wise XOR decoding of an in-memory copy of a file. */

int
main(int ac, char **av)
{
	char *filename;
	char *keystring;
	struct stat sb;
	unsigned char *ciphertext_buffer;
	size_t cc, ciphertext_size;
	FILE *fin;
	int keylen;

	if (ac < 3)
	{
		fprintf(stderr, "xor - decode xor-encoded files, where key is a text string\n");
		fprintf(stderr, "Usage: xor <filename> <keystring>\n");
		fprintf(stderr, "Decoded output on stdout\n");
		exit(1);
	}

	filename = av[1];
	keystring = av[2];
	keylen = strlen(keystring);

	if (-1 == stat(filename, &sb))
	{
		fprintf(stderr, "Could not stat \"%s\": %s\n",
			filename, strerror(errno));
		exit(2);
	}

	ciphertext_size = sb.st_size;

	if (NULL == (fin = fopen(filename, "r")))
	{
		fprintf(stderr, "Could not fopen(%s) for read: %s\n",
			filename, strerror(errno));
		exit(3);
	}

	ciphertext_buffer = malloc(ciphertext_size);

	if (ciphertext_size != (cc = fread(ciphertext_buffer, 1, ciphertext_size, fin)))
	{
		fprintf(stderr, "Wanted to read %lu bytes of ciphertext, read only %lu bytes\n",
			cc, ciphertext_size
		);

		exit(4);
	}

	fprintf(stderr, "Read all %lu bytes of cipher text from \"%s\"\n",
		ciphertext_size, filename);

	fclose(fin);
	
	int idx = 0;
	for (unsigned int i = 0; i < ciphertext_size; ++i)
	{
		unsigned char x = ciphertext_buffer[i] ^ keystring[idx];
		// if (x == '\n') break;
		printf("%c", x);
		++idx;
		idx %= keylen;
	}

	return 0;
}
