SELECT * FROM (
    SELECT *, count(*) AS threads_count FROM (
        SELECT fc.id as category_id, fc.name, fc.description, fc.category_slug, fc.order, fc.icon,
                u.id AS user_id, u.username, u.avatar, ft.created_at
        FROM forum_categories AS fc
        INNER JOIN forum_threads AS ft ON fc.id = ft.forum_category_id
        INNER JOIN users as u ON ft.created_by = u.id
        WHERE ft.is_published = 1
        ORDER BY ft.created_at DESC
    ) AS t1 GROUP BY category_slug
) as t2 ORDER BY `order`