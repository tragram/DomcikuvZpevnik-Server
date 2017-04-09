<?php
    require_once 'makeTextNiceAgain.php';
    $GLOBALS['ini'] = parse_ini_file('config.ini');

    class Song
    {
        private $base_filename;
        private $title;
        private $artist;
        private $has_pdf_gen;
        private $date_added;
        private $language;
        private $id;

        function __construct($id, $title, $artist, $has_pdf_gen, $date_added, $language)
        {
            $this->id=$id;
            $this->title = $title;
            $this->artist = $artist;
            $this->has_pdf_gen = $has_pdf_gen;
            $this->date_added = $date_added;
            $this->language = $language;
            $this->base_filename = $GLOBALS['ini']['files_location'] . makeTextNiceAgain($this->artist . "_" . $this->title);
        }

        /**
         * @return mixed
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @return String
         */
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * @return String
         */
        public function getArtist()
        {
            return $this->artist;
        }

        public function hasGen(){
            return $this->has_pdf_gen;
        }
        /**
         * @return int
         */
        public function getDateAdded()
        {
            return $this->date_added;
        }

        /**
         * @return String
         */
        public function getLanguage()
        {
            return $this->language;
        }

        public function getLanguageCzech(){
            switch($this->language){
                case "CZECH":
                    return "Čeština";
                    break;
                case "SPANISH":
                    return "Španělština";
                    break;
                case "ENGLISH":
                    return "Angličtina";
                    break;
                case "SLOVAK":
                    return "Slovenština";
                    break;
                case "OTHER":
                    return "Ostatní";
                    break;
                default:
                    return "Ostatní";
            }
        }

        public function getSkenURL()
        {
            return $this->base_filename . "-sken.pdf";
        }

        public function getCompURL()
        {
            return $this->base_filename . "-comp.pdf";
        }

        public function getGenURL()
        {
            return $this->base_filename . "-gen.pdf";
        }

        public function getGenLink()
        {   
            if ($this->has_pdf_gen == 0) {
                return "---";
            } else {
                return "<a href=\"" . $this->getGenURL() . "\"class=\"btn btn-primary btn-xs\">Generované</a>";
            }
        }

        public function getChordProURL(){
            return $this->base_filename . "-chordpro.txt";
        }

        public function getChordProButton(){
            $chpFile = $this->getChordProURL();
            if (file_exists($chpFile)) {
                return "<a href=\"chordpro.php?file=" . $chpFile . "\"class=\"btn btn-primary btn-xs\">ChordPro</a>";
            } else {
                return "---";
            }
        }

        public function getSkenButton(){
            return "<a href=\"" . $this->getSkenURL() . "\"class=\"btn btn-primary btn-xs\">Originální</a>";
        }

        public function getCompButton(){
            return "<a href=\"" . $this->getCompURL() . "\"class=\"btn btn-primary btn-xs\">Zkompresované</a>";
        }

    }