<?php


namespace App\Search;


class Search
{

    private string $keyword;
    private $categories;

    /**
     * @return string
     */
    public function getKeyword(): string
    {
        return $this->keyword;
    }

    /**
     * @param string $keyword
     */
    public function setKeyword(string $keyword): void
    {
        $this->keyword = $keyword;
    }

    /**
     * @return array
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param array $categories
     */
    public function setCategories($categories): void
    {
        $this->categories = $categories;
    }



    public function __toString()
    {
        return 'search';
    }


}
