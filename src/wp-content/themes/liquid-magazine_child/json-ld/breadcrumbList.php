<script type="application/ld+json">
<?php
    if(is_single()) {
        get_template_part('/json-ld/breadcrumbLists/single-breadcrumb');
    } elseif(is_page()) {
        get_template_part('./breadcrumbLists/page-breadcrumb');
    } elseif(is_category()) {
        get_template_part('./breadcrumbLists/category-breadcrumb');    
    } elseif(is_tag()) {
        get_template_part('./breadcrumbLists/tag-breadcrumb');
    } elseif(is_author()) {
        get_template_part('./breadcrumbLists/author-breadcrumb');
    } elseif(is_date()) {
        get_template_part('./breadcrumbLists/date-breadcrumb');    
    } elseif(is_search()) {
        get_template_part('./breadcrumbLists/search-breadcrumb');
    } elseif(is_attachment()) {
        get_template_part('./breadcrumbLists/attachment-breadcrumb');
    }
?>
</script>