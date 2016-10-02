<?php 

namespace Insight\ProductDefinitions\Events;

use Insight\Comments\Comment;
use Insight\ProductDefinitions\ProductDefinition;

class ProductDefinitionCommentWasCreated
{

    /**
     * @var Comment
     */
    public $comment;
    /**
     * @var ProductDefinition
     */
    public $productDefinition;

    /**
     * @param Comment $comment
     * @param ProductDefinition $productDefinition
     */
    public function __construct(Comment $comment, ProductDefinition $productDefinition)
    {
        $this->comment = $comment;
        $this->productDefinition = $productDefinition;
    }
}
 