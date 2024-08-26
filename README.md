# 🙂 CustomItems
`Customies`를 이용하여 커스텀 아이템을 로드합니다

## 📕 `custom_items.yml` 작성 방법
```
---
identifier:
  detail: value
...
```
와 같이 작성할 수 있습니다

- `identifier`는 아이템의 고유 식별자이므로 아이템마다 고유한 값을 가져야 합니다

### ⚝ `detail`에 작성해야 하는 필수 항목 모음
- `id`: 아이템의 고유 아이디입니다 정수 형태이며 아이템마다 고유한 값을 가져야 합니다
- `texture`: 아이템의 텍스쳐 이름을 기재합니다
- `name`: 아이템의 이름을 기재합니다

### ⚝ `detail`에 작성할 수 있는 선택 항목 모음
- `lore`: 아이템의 설명을 기재합니다 (아래와 같이 배열 형태로 기재합니다)
```
lore:
 - 안녕하세요
 - 반갑습니다
```
- `type`: 아이템의 타입을 결정할 수 있습니다
  - 현재 지원하는 타입 리스트: `boots`, `chestplate`, `helmet`, `leggings`, `axe`, `hoe`, `pickaxe`, `shovel`, `sword`
- `digger`: 특정 아이템을 빠르게 캐도록 할 수 있습니다
  - 1) `type`: 도구의 특징을 반영합니다 `예) 도구 타입의 아이템이라면 나무를 빠르게 캡니다`
  - 2) `all`: 모든 블럭을 빠르게 캐도록 할 수 있습니다
  - 3) `원하는 블럭과 속도 적기`: (아래와 같이 배열 형태로 기재합니다)
```
digger:
 - "minecraft:oak_wood": 5
```
- `max_durability`: 내구도를 설정할 수 있습니다 (`infinity`를 기재하면 아이템의 내구도가 닳지 않습니다)
- `defense_point`: 아이템의 방어력을 설정할 수 있습니다
- `off_hand`: 왼손에 들 수 있는지 여부를 설정 할 수 있습니다 (`true` 혹은 `false`로 기재합니다)
- `max_stack_size`: 아이템이 몇 개까지 겹쳐질 수 있는지를 설정할 수 있습니다 (일반적으로 아이템은 64, 도구는 1입니다)

## 📖 `custom_items.yml` 작성 예시
```
---
"hello:apple":
  type: pickaxe
  digger: type
  texture: apple
  max_durability: infinity
  id: 4545
  name: 안녕하세요~!
  lore:
    - "안녕하세요!"
    - "반가워요~!"
...
```

## ♻️ 플러그인에 자유롭게 기여해주세요!
- 문서에 설명된 `Component` 외에 `armor_component`, `block_placer`, `canDestroyInCreative`, `chargeable`, `cooldown`, `foil`, `can_always_eat`, `fuel`, `interact_button`, `knockback_resistance` 등의 `Component`들이 구현되어 있습니다.
- 문서에 설명되지 않은 `Component` 사용법 추가와 같은 `문서 기여` 혹은 새로운 `Component` 추가 등의 기여 등 `CustomItems`를 발전시킬 수 있는 모든 기여를 환영합니다 🍺