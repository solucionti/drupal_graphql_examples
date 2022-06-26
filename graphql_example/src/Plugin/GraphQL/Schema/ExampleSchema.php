<?php

namespace Drupal\graphql_examples\Plugin\GraphQL\Schema;

use Drupal\graphql\GraphQL\ResolverBuilder;
use Drupal\graphql\GraphQL\ResolverRegistry;
use Drupal\graphql\Plugin\GraphQL\Schema\SdlSchemaPluginBase;
use Drupal\graphql_examples\Wrappers\QueryConnection;

/**
 * @Schema(
 *   id = "example",
 *   name = "Example schema"
 * )
 */
class ExampleSchema extends SdlSchemaPluginBase
{

  /**
   * {@inheritdoc}
   */
  public function getResolverRegistry()
  {
    $builder = new ResolverBuilder();
    $registry = new ResolverRegistry();

    $this->addQueryFields($registry, $builder);
    $this->addArticleFields($registry, $builder);

    // Re-usable connection type fields.
    $this->addConnectionFields('ArticleConnection', $registry, $builder);

    return $registry;
  }

  /**
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */
  protected function addArticleFields(ResolverRegistry $registry, ResolverBuilder $builder): void
  {
    $registry->addFieldResolver('Article', 'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('Article', 'title',
      $builder->compose(
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent()),
        $builder->produce('uppercase')
          ->map('string', $builder->fromParent())
      )
    );

    $registry->addFieldResolver('Article', 'author',
      $builder->compose(
        $builder->produce('entity_owner')
          ->map('entity', $builder->fromParent()),
        $builder->produce('entity_label')
          ->map('entity', $builder->fromParent())
      )
    );

    $registry->addFieldResolver('Article', 'body',
      $builder->compose(
        $builder->produce('property_path')
          ->map('type', $builder->fromValue('entity:node'))
          ->map('value', $builder->fromParent())
          ->map('path', $builder->fromValue('body.value'))
      )
    );
    /**
     * Referencia a taxonomías ciudad
     */
    $registry->addFieldResolver('Article', 'ciudad',
      $builder->produce('entity_reference')
        ->map('entity', $builder->fromParent())
        ->map('field', $builder->fromValue('field_ciudad'))
    );

    $registry->addFieldResolver('Article', 'ciudadParents',
      $builder->compose(
        $builder->produce('property_path')
          ->map('type', $builder->fromValue('entity:node'))
          ->map('value', $builder->fromParent())
          ->map('path', $builder->fromValue('field_ciudad')),
        $builder->callback(function ($entity) {
          $list = [];
          foreach ([1, 2, 3, 4, 5] as $item) {
            array_push($list, $item);
          }
          return $list;
        })
      )

    );


    $registry->addFieldResolver('TagTerm', 'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('TagTerm', 'name',
      $builder->produce('entity_label')
        ->map('entity', $builder->fromParent())
    );
    $registry->addFieldResolver('TagTerm', 'name',
      $builder->produce('entity_label')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('TagTerm', 'body',
      $builder->compose(
        $builder->produce('property_path')
          ->map('type', $builder->fromValue('entity:taxonomy_term'))
          ->map('value', $builder->fromParent())
          ->map('path', $builder->fromValue('description.value'))
      )
    );
//    $registry->addFieldResolver('TagTerm', 'created',
//      $builder->compose(
//        $builder->produce('property_path')
//          ->map('type', $builder->fromValue('entity:taxonomy_term'))
//          ->map('value', $builder->fromParent())
//          ->map('path', $builder->fromValue('revision_created.value'))
//      )
//    );

    $registry->addFieldResolver('TagTerm', 'created',
      $builder->fromValue('aaa')
    );

    /**
     * Fin Referencia a taxonomías ciudad
     */


    $registry->addFieldResolver('TagTerm', 'parent',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('ParentTerm', 'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('ParentTerm', 'name',
      $builder->produce('entity_label')
        ->map('entity', $builder->fromParent())
    );
    $registry->addFieldResolver('ParentTerm', 'name',
      $builder->produce('entity_label')
        ->map('entity', $builder->fromParent())
    );

    $registry->addFieldResolver('ParentTerm', 'body',
      $builder->compose(
        $builder->produce('property_path')
          ->map('type', $builder->fromValue('entity:taxonomy_term'))
          ->map('value', $builder->fromParent())
          ->map('path', $builder->fromValue('description.value'))
      )
    );
    $registry->addFieldResolver('ParentTerm', 'created',
      $builder->compose(
        $builder->produce('property_path')
          ->map('type', $builder->fromValue('entity:taxonomy_term'))
          ->map('value', $builder->fromParent())
          ->map('path', $builder->fromValue('revision_created.value'))
      )
    );


    $registry->addFieldResolver('Article', 'imagen',
      $builder->compose(
        $builder->produce('property_path')
          ->map('type', $builder->fromValue('entity:node'))
          ->map('value', $builder->fromParent())
          ->map('path', $builder->fromValue('field_imagen_single.entity.field_media_image.entity')),
        $builder->produce('image_url')
          ->map('entity', $builder->fromParent())
          ->map('field', $builder->fromValue('field_image'))
      )
    );
    $registry->addFieldResolver('Article', 'imageData',
      $builder->compose(
        $builder->produce('property_path')
          ->map('type', $builder->fromValue('entity:node'))
          ->map('value', $builder->fromParent())
          ->map('path', $builder->fromValue('field_imagen_single.entity.field_media_image.entity')),
        $builder->produce('image_style_url')
          ->map('entity', $builder->fromParent())
          ->map('field', $builder->fromValue('field_image'))

      )
    );
    $registry->addFieldResolver('Article', 'imagenMedia',
      $builder->compose(
        $builder->produce('property_path')
          ->map('type', $builder->fromValue('entity:node'))
          ->map('value', $builder->fromParent())
          ->map('path', $builder->fromValue('field_imagen_single'))
      )
    );

    $registry->addFieldResolver('MediaImage', 'id',
      $builder->produce('entity_id')
        ->map('entity', $builder->fromParent())
    );

  }

  /**
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */
  protected function addQueryFields(ResolverRegistry $registry, ResolverBuilder $builder): void
  {
    $registry->addFieldResolver('Query', 'article',
      $builder->produce('entity_load')
        ->map('type', $builder->fromValue('node'))
        ->map('bundles', $builder->fromValue(['article']))
        ->map('id', $builder->fromArgument('id'))
    );

    $registry->addFieldResolver('Query', 'articles',
      $builder->produce('query_articles')
        ->map('offset', $builder->fromArgument('offset'))
        ->map('limit', $builder->fromArgument('limit'))
    );
  }

  /**
   * @param string $type
   * @param \Drupal\graphql\GraphQL\ResolverRegistry $registry
   * @param \Drupal\graphql\GraphQL\ResolverBuilder $builder
   */
  protected function addConnectionFields($type, ResolverRegistry $registry, ResolverBuilder $builder): void
  {
    $registry->addFieldResolver($type, 'total',
      $builder->callback(function (QueryConnection $connection) {
        return $connection->total();
      })
    );

    $registry->addFieldResolver($type, 'items',
      $builder->callback(function (QueryConnection $connection) {
        return $connection->items();
      })
    );
  }

}
