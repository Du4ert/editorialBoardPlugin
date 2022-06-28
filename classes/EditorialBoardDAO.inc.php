<?php

import('lib.pkp.classes.db.DAO');
import('plugins.generic.editorialBoard.classes.editorialMember');

class EditorialBoardDAO extends DAO 
{

    function getById($editorialMemberId, $contextId = null)
    {
        $params = [(int) $editorialMemberId];
        if ($contextId) $params[] = (int) $contextId;

        $result = $this->retrieve(
            'SELECT * FROM editorial_board WHERE editorial_member_id = ?'
            . ($contextId?' AND context_id = ?':''),
            $params
        );
        $row = $result->current();
        return $row ? $this->_fromRow((array) $row) : null;
    }

    function getByContextId($contextId, $rangeInfo = null)
    {
        $result = $this->retrieveRange(
            'SELECT * FROM editorial_board WHERE context_id = ?',
            [(int) $contextId],
            $rangeInfo
        );
        return new DAOResultFactory($result, $this, '_fromRow');
    }

    function getByPath($contextId, $path)
    {
        $result = $this->retrieve(
            'SELECT * FROM editorial_board WHERE context_id = ? AND path = ?',
            [(int) $contextId, $path]
        );
        $row = $result->current();
        return $row ? $this->_fromRow((array) $row) : null;
    }

    function insertObject($editorialMember)
    {
        $this->update(
            'INSERT INTO editorial_board (context_id, path) VALUES (?, ?)',
            [(int) $editorialMember->getContextId(), $editorialMember->getPath()]
        );

        $editorialMember->setId($this->getInsertId());
        $this->updateLocaleFields($editorialMember);
    }

	function updateObject($editorialMember) 
    {
		$this->update(
			'UPDATE	editorial_board
			SET	context_id = ?,
				path = ?
			WHERE	editorial_member_id = ?',
			[
				(int) $editorialMember->getContextId(),
				$editorialMember->getPath(),
				(int) $editorialMember->getId()
			]
		);
		$this->updateLocaleFields($editorialMember);
	}

    function deleteById($editorialMemberId)
    {
        $this->update(
            'DELETE FROM editorial_board WHERE editorial_member_id = ?',
            [(int) $editorialMemberId]
        );
        $this->update(
            'DELETE FROM editorial_member_settings WHERE editorial_member_id = ?',
            [(int) $editorialMemberId]
        );
    }

    function deleteObject($editorialMember)
    {
        $this->deleteById($editorialMember->getId());
    }

    function newDataObject()
    {
        return new EditorialMember();
    }

    function _fromRow($row)
    {
        $editorialMember = $this->newDataObject();
        $editorialMember->setId($row['editorial_member_id']);
        $editorialMember->setPath($row['path']);
        $editorialMember->setContextId($row['context_id']);

        $this->getDataObjectSettings('editorial_member_settings', 'editorial_member_id', $row['editorial_member_id'], $editorialMember);
        return $editorialMember;
    }

    function getInsertId()
    {
        return $this->_getInsertId('editorial_board', 'editorial_member_id');
    }

    function getLocaleFieldNames()
    {
        return ['title', 'affiliation', 'bio'];
    }

    function updateLocaleFields(&$editorialMember)
    {
        $this->updateDataObjectSettings('editorial_member_settings', $editorialMember,
            ['editorial_member_id' => $editorialMember->getId()]
        );
    }

	function getAdditionalFieldNames() {
		return array_merge(parent::getAdditionalFieldNames(), [
			'references',
		]);
	}

}