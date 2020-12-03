CREATE PROCEDURE update_default_referent_id()
BEGIN
    UPDATE item SET referent_id = id WHERE referent_id = 0;
END;


CREATE TRIGGER after_insert_item
    AFTER INSERT
    ON item
    FOR EACH ROW
BEGIN
    CALL update_default_referent_id();
END;
