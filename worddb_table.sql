DROP TABLE member;
CREATE TABLE sample(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    chapter TINYINT UNSIGNED,
    section TINYINT UNSIGNED,
    term VARCHAR(500),
    explanation VARCHAR(5000),
    PRIMARY KEY(id)
);
INSERT INTO sample (id, chapter, section, term, explanation) VALUES(10100001, 1, 1, '基数', 'n進数という時のnのことという認識でいい。');
INSERT INTO sample (id, chapter, section, term, explanation) VALUES(10100002, 1, 1, '仮数', 'n進数の値を10進数で計算するときに基数にかける値のこと。');
INSERT INTO sample (id, chapter, section, term, explanation) VALUES(10100003, 1, 1, '重み', 'n進数の値を10進数で計算するときに仮数*n^(桁数-1)とするがn^(桁数-1)の部分のことを重みという。');
INSERT INTO sample (id, chapter, section, term, explanation) VALUES(10100004, 1, 1, 'シフト演算において算術シフトと論理シフトの違いは?', 
'シフト演算にて追加するビットの扱いが主に違う。これは算術シフトでは符号ビットを考慮するためである。');
INSERT INTO sample (id, chapter, section, term, explanation) VALUES(10100005, 1, 1, '排他的論理和はどのような論理演算か?真理値は?3変数以上の場合はどのように計算されるか?', 
'排他的論理和は1の数が奇数の時に1を返す。');
INSERT INTO sample (id, chapter, section, term, explanation) VALUES(10100006, 1, 1, '等価演算とはどのような演算か?', '基本的には排他的論理和を否定した真理値になる。');