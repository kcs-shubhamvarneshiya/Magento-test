Cms Block Random
===

The module provides a widget that allows the random display of a CMS block.

On load, the widget will pick one random identity ID from the widget parameter ‘identity_ids’. Then the content for the selected CMS Block identity will be loaded via GraphQL and rendered on the page. 

If the CMS Block response data is null for the selected identity_id, the javascript will attempt to load another CMS block from the list of identity_ids, making sure not to load one already attempted.
