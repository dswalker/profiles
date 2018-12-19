<?php

namespace App\Entity;

use RenanBr\BibTexParser\Listener;
use RenanBr\BibTexParser\Parser;

class Citation
{
    ##########################
    #  STANDARD ENTRY TYPES  #
    ##########################
    
    /**
     * An article from a journal or magazine.
     */
    CONST ARTICLE = "article";
    
    /**
     * A book with an explicit publisher.
     */
    CONST BOOK = "book";
    
    /**
     * A work that is printed and bound, but without a named publisher or 
     * sponsoring institution.
     */
    CONST BOOKLET = "booklet";
    
    /**
     * The same as inproceedings.
     */
    CONST CONFERENCE = "conference";
    
    /**
     * A part of a book, which may be a chapter (or section or whatever) and/or
     * a range of pages.
     */
    CONST IN_BOOK = "inbook";
    
    /**
     * A part of a book having its own title.
     */
    CONST IN_COLLECTION = "incollection";
    
    /**
     * An article in a conference proceedings.
     */
    CONST IN_PROCEEDINGS = "inproceedings";
    
    /**
     * Technical documentation.
     */
    CONST MANUAL = "manual";
    
    /**
     * A Master's thesis.
     */
    CONST MASTER_THESIS = "mastersthesis";
    
    /**
     * Use this type when nothing else fits.
     */
    CONST MISC = "misc";

    /**
     * A PhD thesis.
     */
    CONST PHD_THESIS = "phdthesis";
    
    /**
     * The proceedings of a conference.
     */
    CONST PROCEEDINGS = "proceedings";
    
    /**
     * A report published by a school or other institution, usually numbered 
     * within a series.
     */
    CONST TECH_REPORT = "techreport";

    /**
     * A document having an author and title, but not formally published.
     */
    CONST UNPUBLISHED = "unpublished";
    
    #####################
    #  STANDARD FIELDS  #
    #####################
    
    /**
     * Usually the address of the publisher or other type of institution.
     *  
     * For major publishing houses, van Leunen recommends omitting the 
     * information entirely. For small publishers, on the other hand, you can 
     * help the reader by giving the complete address.
     * @var string
     */
    public $address;
 
    /**
     * An annotation.
     *  
     *  It is not used by the standard bibliography styles, but may be used by 
     *  others that produce an annotated bibliography.
     * @var string
     */
    public $annote;
    
    /**
     *  The name(s) of the author(s), in the format described in the LaTeX book.
     * @var string
     */
    public $author;
    
    /**
     * Title of a book, part of which is being cited.
     * 
     * See the LaTeX book for how to type titles. For book entries, use the 
     * title field instead.
     * @var string
     */
    public $booktitle;
    
    /**
     * A chapter (or section or whatever) number.
     * @var string
     */
    public $chapter;
    
    /**
     * The database key of the entry being cross referenced.
     * 
     * Any fields that are missing from the current record are inherited from
     * the field being cross referenced.
     * @var string
     */
    public $crossref;
    
    /**
     *  The edition of a book --for example, "Second".
     *  
     * This should be an ordinal, and should have the first letter capitalized,
     * as shown here; the standard styles convert to lower case when necessary.
     * @var string
     */
    public $edition;
    
    /**
     * Name(s) of editor(s), typed as indicated in the LaTeX book.
     * 
     * If there is also an author field, then the editor field gives the editor
     * of the book or collection in which the reference appears.
     * @var string
     */
    public $editor;
    
    /**
     * How something strange has been published.
     * 
     * The first word should be capitalized.
     * @var string
     */
    public $howpublished;
    
    /**
     * The sponsoring institution of a technical report.
     * 
     * @var string
     */
    public $institution;
    
    /**
     * A journal name.
     * 
     * Abbreviations are provided for many journals.
     * @var string
     */
    public $journal;
    
    /**
     * Used for alphabetizing, cross referencing, and creating a label when the 
     * "author" information is missing. This field should not be confused with 
     * the key that appears in the cite command and at the beginning of the 
     * database entry.
     * @var string
     */
    public $key;
    
    /**
     * The month in which the work was published or, for an unpublished work, 
     * in which it was written.
     * 
     * You should use the standard three-letter abbreviation, as described in 
     * Appendix B.1.3 of the LaTeX book.
     * @var string
     */
    public $month;
    
    /**
     * Any additional information that can help the reader.
     * 
     * The first word should be capitalized.
     * @var string
     */
    public $note;
    
    /**
     * The number of a journal, magazine, technical report, or of a work in a 
     * series.
     * 
     * An issue of a journal or magazine is usually identified by its volume 
     * and number; the organization that issues a technical report usually gives
     * it a number; and sometimes books are given numbers in a named series.
     * @var string
     */
    public $number;
    
    /**
     * The organization that sponsors a conference or that publishes a manual.
     * @var string
     */
    public $organization;
    
    /**
     * One or more page numbers or range of numbers, such as 42--111 or 
     * 7,41,73--97 or 43+ (the `+' in this last example indicates pages 
     * following that don't form a simple range). To make it easier to maintain
     * Scribe-compatible databases, the standard styles convert a single dash 
     * (as in 7-33) to the double dash used in TeX to denote number ranges 
     * (as in 7--33).
     * @var string
     */
    public $pages;
    
    /**
     * The publisher's name
     * @var string
     */
    public $publisher;
    
    /**
     * The name of the school where a thesis was written.
     * @var string
     */
    public $school;
    
    /**
     * The name of a series or set of books.
     * 
     * When citing an entire book, the the title field gives its title and an
     * optional series field gives the name of a series or multi-volume set in 
     * which the book is published.
     * @var string
     */
    public $series;

    /**
     * The work's title, typed as explained in the LaTeX book.
     * @var string
     */
    public $title;
    
    /**
     * The type of a technical report---for example, "Research Note".
     * @var string
     */
    public $type;
    
    /**
     * The volume of a journal or multi-volume book.
     * @var string
     */
    public $volume;

    /**
     * The year of publication or, for an unpublished work, the year it was 
     * written.
     * 
     * Generally it should consist of four numerals, such as 1984, although the 
     * standard styles can handle any year whose last four nonpunctuation 
     * characters are numerals, such as `\hbox{(about 1984)}'.
     * @var string
     */
    public $year;
    
    
    ####################
    #   OTHER FIELDS   #
    ####################
    
    /**
     * An abstract of the work.
     * @var string
     */
    public $abstract;
    
    /**
     * A Table of Contents
     * @var string
     */
    public $contents;
    
    /**
     * Copyright information.
     * @var string
     */
    public $copyright;
    
    /**
     * Digital object identifier
     * @var string
     */
    public $doi;
    
    /**
     * The International Standard Book Number.
     * @var string
     */
    public $isbn;
    
    /**
     * The International Standard Serial Number.
     * @var string
     */
    public $issn;
    
    /**
     * Key words used for searching or possibly for annotation.
     * @var string
     */
    public $keywords;
    
    /**
     * The language the document is in.
     * @var string
     */
    public $language;
    
    /**
     * The Library of Congress Call Number
     * @var string
     */
    public $lccn;
    
    /**
     * The price of the document.
     * @var string
     */
    public $price;
    
    /**
     * The WWW Universal Resource Locator that points to the item being
     * referenced.
     * 
     * This often is used for technical reports to point to the ftp site where 
     * the postscript source of the report is located.
     * @var string
     */
    public $url;
    

    #################
    #   UTILITIES   #
    #################
    
    
    /**
     * Populate Citation from a BibTeX formatted string
     *
     * @param string $bibtex
     */
    public function fromBibtex(string $bibtex)
    {
        $parser = new Parser();
        $listener = new Listener();
        
        $parser->addListener($listener);
        $parser->parseString($bibtex);
        $array = $listener->export();
        
        $this->fromArray($array[0]);
    }
    
    /**
     * Populate Citation from json
     *
     * @param string $bibtex
     */
    public function fromJson(string $json)
    {
        $array = json_decode($json, true);
        $this->fromArray($array);
    }
    
    /**
     * Populate Citation from array
     *
     * @param array $array
     */
    public function fromArray(array $array)
    {
        foreach ($array as $key => $value) {
            if (property_exists(__CLASS__, $key)) {
                $this->$key = $value;
            }
        }
    }
    
    /**
     * Json-encoded string
     *
     * @return string
     */
    public function toArray()
    {
        return array_filter(get_object_vars($this));
    }
}
