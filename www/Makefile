INFILES = $(shell find . -name "index.mdwn")
OUTFILES = $(INFILES:.mdwn=.html)

all: $(OUTFILES)

%.html: %.mdwn footer.inc header.inc
	@cat header.inc > $@
	@# First seen comment becomes page title
	@sed -n  '/<!--/{s/<!-- *//;s/ *-->//;p;q; }' $< >> $@
	@echo "</title></head><body>" >> $@
	@cmark $<  >> $@
	@cat footer.inc >> $@
	@echo $< '→' $@

clean:
	rm -f $(OUTFILES)
