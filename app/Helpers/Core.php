<?php
namespace App\Helpers;

use App\Models\Category;

class Core {
    public function getCategory() {
        return Category::all();
    }

    function removeSemicolons($html)
    {
        return str_replace(';', '', $html);
    }

    public function getCategoriesWithChildren($categories ,$parent = null) {
        $result = [];

        foreach ($categories as $category) {
            if ($category->parent_id === $parent) {
                $category->children = $this->getCategoriesWithChildren($categories, $category->id);
                $result[] = $category;
            }
        }
    
        return $result;
    }

    public function getCategoryProduct() {
        $categories = Category::where('type', 'PRODUCT')->get();

        return $this->getCategoriesWithChildren($categories);
    }

    public function getElementByClassname ($html, $classname) {
        // Load HTML content into a DOM object
        // create new DOMDocument
        $dom = new \DOMDocument('1.0', 'UTF-8');

        $dom->recover = true;
        $dom->strictErrorChecking = false;
        @$dom->loadHTML($html);
        // libxml_clear_errors();
        $xpath = new \DOMXpath($dom);
        $nodes = $xpath->query('//li[@class="' . $classname . '"]');
      
        $tmp_dom = new \DOMDocument();
        foreach ($nodes as $node) {
          $tmp_dom->appendChild($tmp_dom->importNode($node, true));
        }
      
        return trim($tmp_dom->saveHTML());
      }
}