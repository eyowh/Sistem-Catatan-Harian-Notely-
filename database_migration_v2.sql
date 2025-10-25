-- Notely schema extension (v2)

-- Workspaces
CREATE TABLE IF NOT EXISTS workspaces (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  name VARCHAR(191) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY user_name_unique (user_id, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tags
CREATE TABLE IF NOT EXISTS tags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  UNIQUE KEY user_tag_unique (user_id, name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Note <-> Tags
CREATE TABLE IF NOT EXISTS note_tags (
  note_id INT NOT NULL,
  tag_id INT NOT NULL,
  PRIMARY KEY (note_id, tag_id),
  FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE,
  FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sharing
CREATE TABLE IF NOT EXISTS shared_notes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  note_id INT NOT NULL,
  owner_id INT NOT NULL,
  target_user_id INT NULL,
  target_email VARCHAR(191) NULL,
  permission ENUM('read','write') NOT NULL DEFAULT 'read',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE,
  FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (target_user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Reminders
CREATE TABLE IF NOT EXISTS reminders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  note_id INT NOT NULL,
  user_id INT NOT NULL,
  remind_at DATETIME NOT NULL,
  message VARCHAR(255) NOT NULL,
  sent TINYINT(1) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX (remind_at, sent)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Extend notes
ALTER TABLE notes
  ADD COLUMN IF NOT EXISTS workspace_id INT NULL AFTER user_id,
  ADD COLUMN IF NOT EXISTS is_pinned TINYINT(1) DEFAULT 0,
  ADD COLUMN IF NOT EXISTS views INT DEFAULT 0,
  ADD COLUMN IF NOT EXISTS sort_order INT DEFAULT 0,
  ADD FOREIGN KEY (workspace_id) REFERENCES workspaces(id) ON DELETE SET NULL,
  ADD INDEX (workspace_id),
  ADD INDEX (is_pinned),
  ADD INDEX (updated_at);
